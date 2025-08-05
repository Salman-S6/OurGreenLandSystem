<?php

namespace Modules\FarmLand\Services;

use App\Interfaces\BaseCrudServiceInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\FarmLand\Models\Land;
use Modules\FarmLand\Models\Rehabilitation;

class RehabilitationService implements BaseCrudServiceInterface
{
    protected string $cacheKeyAll = 'allRehabilitations';

    /**
     * Store a new rehabilitation record along with related lands and update their rehabilitation dates.
     */
    public function store(array $data): Rehabilitation
    {
        try {
            return DB::transaction(function () use ($data) {
                $rehabilitation = new Rehabilitation();

                $this->setTranslations($rehabilitation, $data);

                $rehabilitation->save();

                $landsPivotData = $this->prepareLandsPivotData($data['lands'] ?? []);
                $rehabilitation->lands()->sync($landsPivotData);
                $this->updateLandsRehabilitationDates($landsPivotData);

                Cache::forget($this->cacheKeyAll);

                return $rehabilitation;
            });
        } catch (\Exception $e) {
            Log::error('Failed to create rehabilitation: ' . $e->getMessage(), ['data' => $data]);
            throw $e;
        }
    }

    /**
     * Update an existing rehabilitation record and its associated lands.
     */
    public function update(array $data, Model $rehabilitation): Rehabilitation
    {
        return DB::transaction(function () use ($data, $rehabilitation) {
            $this->setTranslations($rehabilitation, $data);
            $rehabilitation->save();

            // Update pivot if lands are sent
            if (!empty($data['lands']) && is_array($data['lands'])) {
                $landsPivotData = $this->prepareLandsPivotData($data['lands']);
                $rehabilitation->lands()->syncWithoutDetaching($landsPivotData);
                $this->updateLandsRehabilitationDates($landsPivotData);
            }

            Cache::forget($this->cacheKeyAll);
            Cache::forget("rehabilitation_{$rehabilitation->id}");

            return $rehabilitation;
        });
    }

    /**
     * Get all rehabilitation records (cached).
     */
    public function getAll(array $filters = []): iterable
    {
        return Cache::remember($this->cacheKeyAll, now()->addMinutes(10), function () {
            return Rehabilitation::latest()->get();
        });
    }

    /**
     * Get single rehabilitation record with lands (cached).
     */
    public function get(Model $model): Rehabilitation
    {
        $cacheKey = "rehabilitation_{$model->id}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($model) {
            return Rehabilitation::with('lands')->findOrFail($model->id);
        });
    }

    /**
     * Delete rehabilitation record and clear caches.
     */
    public function destroy(Model $rehabilitation): bool
    {
        $deleted = $rehabilitation->delete();

        Cache::forget($this->cacheKeyAll);
        Cache::forget("rehabilitation_{$rehabilitation->id}");

        return $deleted;
    }

    /**
     * Set translations for all translatable fields.
     */
    protected function setTranslations(Model $rehabilitation, array $data): void
    {
        $translatableFields = ['description', 'notes', 'event'];
        $locales = ['ar', 'en'];

        foreach ($translatableFields as $field) {
            foreach ($locales as $locale) {
                $key = "{$field}_{$locale}";

                if (isset($data[$key])) {
                    $rehabilitation->setTranslation($field, $locale, $data[$key]);
                }
            }
        }
    }

    /**
     * Prepare lands pivot data for sync or update.
     */
    protected function prepareLandsPivotData(array $lands): array
    {
        $pivotData = [];

        foreach ($lands as $landData) {
            if (empty($landData['land_id'])) {
                throw new \InvalidArgumentException("Land ID is required for rehabilitation.");
            }

            $pivotData[$landData['land_id']] = [
                'performed_by' => $landData['performed_by'] ?? Auth::id(),
                'performed_at' => $landData['performed_at'] ?? now()->toDateString(),
                'updated_at'   => now(),
            ];
        }

        return $pivotData;
    }

    /**
     * Update `rehabilitation_date` on each land if the performed_at is newer.
     */
/**
 * Update `rehabilitation_date` on each land if the performed_at is newer,
 * optimized by batch updating lands to avoid N+1 query problem.
 *
 * @param array $landsPivotData
 * @return void
 */
protected function updateLandsRehabilitationDates(array $landsPivotData): void
{
    $landIds = array_keys($landsPivotData);

    // جلب الأراضي المرتبطة دفعة واحدة
    $lands = Land::whereIn('id', $landIds)->get(['id', 'rehabilitation_date']);

    $landsToUpdate = [];

    foreach ($lands as $land) {
        $pivot = $landsPivotData[$land->id] ?? null;

        if (!$pivot || empty($pivot['performed_at'])) {
            continue; // تخطى إذا لا يوجد تاريخ أداء
        }

        $performedDate = Carbon::parse($pivot['performed_at']);
        $currentDate = $land->rehabilitation_date ? Carbon::parse($land->rehabilitation_date) : null;

        // فقط حدث إذا التاريخ الجديد أحدث من الحالي أو التاريخ الحالي فارغ
        if (is_null($currentDate) || $performedDate->gt($currentDate)) {
            $landsToUpdate[$land->id] = $performedDate->toDateString();
        }
    }

    // تحديث دفعة واحدة باستخدام Query Builder
    foreach ($landsToUpdate as $landId => $rehabDate) {
        Land::where('id', $landId)->update(['rehabilitation_date' => $rehabDate]);
    }
}

}
