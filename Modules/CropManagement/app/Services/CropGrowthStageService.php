<?php

namespace Modules\CropManagement\Services;

use App\Interfaces\BaseCrudServiceInterface;
use Modules\CropManagement\Models\CropGrowthStage;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Repository for handling Crop Growth Stage operations.
 */
class CropGrowthStageService implements BaseCrudServiceInterface
{
    /**
     * Retrieve all crop growth stages with optional filtering by crop plan.
     *
     * @param int $perPage
     * @param int|null $cropPlanId
     * @param int $userId
     * @return LengthAwarePaginator
     */
    public function getAll(array $filters): LengthAwarePaginator
    {
        // $query = CropGrowthStage::query()
        //     ->with(['cropPlan', 'recorder'])
        //     ->when($cropPlanId, fn($q) => $q->where('crop_plan_id', $cropPlanId))
        //     ->where('recorded_by', $userId);

        // return $query->paginate($perPage);
    }

    /**
     * Store a new crop growth stage.
     *
     * @param array $data
     * @param int $userId
     * @return CropGrowthStage
     */
    public function store(array $data, int $userId): CropGrowthStage
    {
        $data['recorded_by'] = $userId;
        return CropGrowthStage::create($data);
    }

    /**
     * Update an existing crop growth stage.
     *
     * @param CropGrowthStage $stage
     * @param array $data
     * @param int $userId
     * @return CropGrowthStage
     */
    public function update(CropGrowthStage $stage, array $data, int $userId): CropGrowthStage
    {
        $data['recorded_by'] = $userId;
        $stage->update($data);
        return $stage->refresh();
    }

    /**
     * Delete a crop growth stage.
     *
     * @param CropGrowthStage $stage
     * @return void
     */
    public function destroy(CropGrowthStage $stage): void
    {
        $stage->delete();
    }
}
