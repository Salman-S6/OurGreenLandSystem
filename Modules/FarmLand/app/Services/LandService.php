<?php

namespace Modules\FarmLand\Services;

use Illuminate\Support\Facades\DB;
use Modules\FarmLand\Models\Land;

class LandService
{
    public function handle() {}


    /**
     * Get lands sorted by rehabilitation priority (high → low).
     */
    public function getPrioritizedLands()
    {
        return Land::with(['farmer', 'soilType'])
                   ->prioritized()
                   ->get();
    }
     /**
     * Return all lands with their related data.
     */
    public function getAllLands()
    {
           return Land::with(['user', 'farmer', 'soilType', 'rehabilitations'])
            ->latest()
            ->get();
    }

    /**
     * Return details for a specific land.
     */
    public function show(int $id)
    {
        return Land::with(['soilType'])->findOrFail($id)->toArray();
    }

    /**
     * Store a new land record.
     */
    public function store(array $data): Land
    {
        return DB::transaction(function () use ($data) {
            return Land::create($data);
        });
    }

    /**
     * Update an existing land record.
     */
    public function update(array $data,Land $land)
    {
        $land->update($data);
        return $land;
    }

    /**
     * Delete a land record.
     */
    public function destroy(Land $land): bool
    {
        return $land->delete();
    }
}
