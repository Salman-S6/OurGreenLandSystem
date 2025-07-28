<?php

namespace Modules\FarmLand\Services;

use App\Services\BaseCrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\FarmLand\Models\Land;

class LandService extends BaseCrudService
{
    public function __construct(Land $model)
    {
        parent::__construct($model);
    }

    /**
     * Get lands sorted by rehabilitation priority (high → low), with caching.
     */
    public function getPrioritizedLands()
    {
        return Cache::remember('lands_prioritized', now()->addMinutes(10), function () {
            return Land::with(['farmer', 'soilType'])
                ->prioritized()
                ->get();
        });
    }

    /**
     * Return all lands with their related data, with caching.
     */
    public function getAll(array $filters = []): iterable
    {
        return Cache::remember('lands_all', now()->addMinutes(10), function () {
            return Land::with(['user', 'farmer', 'soilType', 'rehabilitations'])
                ->latest()
                ->get();
        });
    }

    /**
     * Return details for a specific land with related soil type, with caching.
     *
     * @param Model $land
     */
    public function get(Model $land): Land
    {
        return Cache::remember("land_show_{$land->id}", now()->addMinutes(10), function () use ($land) {
            return $land->load(['soilType']);
        });
    }

    /**
     * Store a new land record and cache it.
     *
     * @param array $data
     */
    public function store(array $data): Land
    {
        $land = parent::store($data);

        Cache::forget('lands_all');
        Cache::forget('lands_prioritized');
        Cache::put("land_show_{$land->id}", $land->load(['soilType']), now()->addMinutes(10));

        return $land;
    }

    /**
     * Update an existing land record and refresh cache.
     *
     * @param array $data
     * @param Model $land
     */
    public function update(array $data, Model $land): Land
    {
        $updated = parent::update($data, $land);

        Cache::forget('lands_all');
        Cache::forget('lands_prioritized');
        Cache::forget("land_show_{$land->id}");

        return $updated;
    }

    /**
     * Delete a land record and clear related cache.
     *
     * @param Model $land
     */
    public function destroy(Model $land): bool
    {
        $deleted = parent::destroy($land);

        Cache::forget('lands_all');
        Cache::forget('lands_prioritized');
        Cache::forget("land_show_{$land->id}");

        return $deleted;
    }
}
