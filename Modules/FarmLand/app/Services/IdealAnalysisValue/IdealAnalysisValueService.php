<?php

namespace Modules\FarmLand\Services\IdealAnalysisValue;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use App\Interfaces\BaseCrudServiceInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\FarmLand\Models\IdealAnalysisValue;

class IdealAnalysisValueService implements BaseCrudServiceInterface
{
    /**
     * Get all Ideal Analysis Values.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        $cacheKey = "ideal_analyses_all";
        return Cache::remember($cacheKey, now()->addHours(1), function () {
            return IdealAnalysisValue::with('crop')->paginate(15);
        });
    }

    /**
     * Get an Ideal Analysis Value.
     *
     * @param mixed $idealAnalysisValue
     * @return Model
     */
    public function get($idealAnalysisValue): Model
    {
        $cacheKey = "ideal_analysis_{$idealAnalysisValue->id}";
        return Cache::remember($cacheKey, now()->addHours(1), function () use ($idealAnalysisValue) {
            return $idealAnalysisValue->load('crop');
        });
    }

    /**
     * Store Ideal Analysis Value.
     *
     * @param mixed $data
     * @return Model
     */
    public function store($data): Model
    {
        $idealAnalysisValue = IdealAnalysisValue::create($data);
        $idealAnalysisValue->load('crop');
        Cache::forget("ideal_analyses_all");
        Cache::forget("ideal_analysis_{$idealAnalysisValue->id}");

        return $idealAnalysisValue;
    }

    /**
     * Update Ideal Analysis Value.
     *
     * @param mixed $data
     * @param mixed $idealAnalysisValue
     * @return Model
     */
    public function update($data, $idealAnalysisValue): Model
    {
        $idealAnalysisValue->update($data);
        $idealAnalysisValue->load('crop');
        Cache::forget("ideal_analyses_all");
        Cache::forget("ideal_analysis_{$idealAnalysisValue->id}");

        return $idealAnalysisValue;
    }

    /**
     * Delete an Ideal Analysis Value.
     *
     * @param mixed $idealAnalysisValue
     * @return bool
     */
    public function destroy($idealAnalysisValue): bool
    {
        Cache::forget("ideal_analyses_all");
        Cache::forget("ideal_analysis_{$idealAnalysisValue->id}");
        return $idealAnalysisValue->delete();
    }
}
