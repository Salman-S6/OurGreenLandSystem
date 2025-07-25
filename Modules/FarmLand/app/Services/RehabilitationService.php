<?php

namespace Modules\FarmLand\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\FarmLand\Models\Rehabilitation;

class RehabilitationService
{

        protected string $cacheKeyAll = 'allRehabilitations';

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    /**
     * Store a new rehabilitation event in the database with relations.
     * 
     * @param array $data
     * @return Rehabilitation
     * 
     * @throws \Exception
     */
    public function store(array $data)
    {

         try {
         return DB::transaction(function () use ($data) {

            $rehabilitation = new Rehabilitation();

            $rehabilitation->setTranslation('description', 'ar', $data['description_ar'] ?? null);
            $rehabilitation->setTranslation('description', 'en', $data['description_en'] ?? null);

            $rehabilitation->setTranslation('notes', 'ar', $data['notes_ar'] ?? null);
            $rehabilitation->setTranslation('notes', 'en', $data['notes_en'] ?? null);

            $rehabilitation->setTranslation('event', 'ar', $data['event_ar'] ?? null);
            $rehabilitation->setTranslation('event', 'en', $data['event_en'] ?? null);
            $rehabilitation->save();

            if (!empty($data['lands']) && is_array($data['lands'])) {
                $landsPivotData = [];

                foreach ($data['lands'] as $landData) {
                    if (empty($landData['land_id'])) {
                        throw new \InvalidArgumentException("Land ID is required for rehabilitation.");
                    }

                    $landsPivotData[$landData['land_id']] = [
                        'performed_by' => $landData['performed_by'] ?? Auth::user()->id,
                        'performed_at' => $landData['performed_at'] ?? now()->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                $rehabilitation->lands()->sync($landsPivotData);
            }

                Cache::forget($this->cacheKeyAll);

            return $rehabilitation;
        });
    } catch (\Exception $e) {
        Log::error('Failed to create rehabilitation: ' . $e->getMessage(), ['data' => $data]);

        throw $e;
    }
    }
    /**
     * Get all rehabilitation events, cached for performance.
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
            return Cache::remember($this->cacheKeyAll, now()->addMinutes(10), function () {
            return Rehabilitation::latest()->get();
        });
    }
    /**
     * Get a single rehabilitation event by ID, cached for performance.
     * 
     * @param int $id
     * @return Rehabilitation
     */
    public function getById(int $id): Rehabilitation
    {
        $cacheKey = "rehabilitation_{$id}";

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($id) {
            return Rehabilitation::findOrFail($id);
        });
    }

    /**
     * Update a rehabilitation event and clear related caches.
     * 
     * @param Rehabilitation $rehabilitation
     * @param array $data
     * @return Rehabilitation
     */
    public function update(Rehabilitation $rehabilitation, array $data): Rehabilitation
    {
    
        if (isset($data['description_ar'])) {
            $rehabilitation->setTranslation('description', 'ar', $data['description_ar']);
        }
        if (isset($data['description_en'])) {
            $rehabilitation->setTranslation('description', 'en', $data['description_en']);
        }
        if (isset($data['notes_ar'])) {
            $rehabilitation->setTranslation('notes', 'ar', $data['notes_ar']);
        }
        if (isset($data['notes_en'])) {
            $rehabilitation->setTranslation('notes', 'en', $data['notes_en']);
        }
        if (isset($data['event_ar'])) {
           $rehabilitation->setTranslation('event', 'ar', $data['event_ar']);
        }
        if (isset($data['event_en'])) {
            $rehabilitation->setTranslation('event', 'en', $data['event_en']);
        }

        $rehabilitation->save();

        Cache::forget($this->cacheKeyAll);
        Cache::forget("rehabilitation_{$rehabilitation->id}");

        return $rehabilitation;
    }

    /**
     * Delete a rehabilitation event and clear related caches.
     * 
     * @param Rehabilitation $rehabilitation
     * @return void
     */
    public function delete(Rehabilitation $rehabilitation): void
    {
        $rehabilitation->delete();

        Cache::forget($this->cacheKeyAll);
        Cache::forget("rehabilitation_{$rehabilitation->id}");
    }
}
