<?php

namespace Modules\FarmLand\Services;

use App\Helpers\GeoHelper;
use App\Services\BaseCrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\FarmLand\Models\Land;

class LandService extends BaseCrudService
{
    public function __construct(Land $model)
    {
        parent::__construct($model);
    }

        /**
     * Store a new land after verifying or calculating its area
     */
    public function store(array $data): Land
    {

        if (!empty($data['boundary_coordinates']) && is_array($data['boundary_coordinates'])) {
            $calculatedArea = GeoHelper::calculatePolygonArea($data['boundary_coordinates']);

            if (!empty($data['area'])) {
                $userArea = round((float) $data['area'], 2);
                if (abs($calculatedArea - $userArea) > 0.1) {
              
                    throw ValidationException::withMessages([
                        'area' => ["Provided area ($userArea ha) does not match calculated area ($calculatedArea ha)."],
                    ]);
                }
            } else {
                $data['area'] = $calculatedArea;
            }
        }

        return $this->model->create($data);
    }


    /**
     * Get lands sorted by rehabilitation priority (high → low), with caching.
     */
    public function getPrioritizedLands()
    {
            return Land::with(['farmer', 'soilType'])
                ->prioritized()
                ->get();
    }

    public function update(array $data , Model $land): Land
{

    $currentBoundary = $land->boundary_coordinates ?? [];
    $newBoundary = $data['boundary_coordinates'] ?? $currentBoundary;

    if (!empty($newBoundary) && is_array($newBoundary)) {
        $calculatedArea = GeoHelper::calculatePolygonArea($newBoundary);

        if (array_key_exists('area', $data)) {
            $userArea = round((float) $data['area'], 2);
            if (abs($calculatedArea - $userArea) > 0.1) {
                throw ValidationException::withMessages([
                    'area' => ["Provided area ($userArea ha) does not match calculated area ($calculatedArea ha)."],
                ]);
            }
        } else {
            $data['area'] = $calculatedArea;
        }
    }

    $land->update($data);

    return $land;
}



}
