<?php

namespace Modules\CropManagement\Services\Crops;

use App\Services\BaseCrudService;
use Modules\CropManagement\Models\BestAgriculturalPractice;
use Illuminate\Database\Eloquent\Model;

class BestAgriculturalPracticeService extends BaseCrudService
{
    /**
     * CropGrowthStageService constructor.
     */
    public function __construct(BestAgriculturalPractice $model)
    {
        parent::__construct($model);
    }

    protected function applyFilters($query, array $filters): void
    {
        if (isset($filters['growth_stage_id'])) {
            $query->where('growth_stage_id', $filters['growth_stage_id']);
        }

        if (isset($filters['expert_id'])) {
            $query->where('expert_id', $filters['expert_id']);
        }

    }

     /**
     * Force delete
     *
     * @param Model $model
     * @return bool
     */
    public function forceDestroy(Model $model): bool
    {
        return $this->handle(fn() => $model->forceDelete());
    }
}
