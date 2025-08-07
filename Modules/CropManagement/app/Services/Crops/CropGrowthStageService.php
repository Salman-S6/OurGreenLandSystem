<?php

namespace Modules\CropManagement\Services\Crops;

use App\Services\BaseCrudService;
use Modules\CropManagement\Models\CropGrowthStage;
use Illuminate\Database\Eloquent\Model;

class CropGrowthStageService extends BaseCrudService
{
    /**
     * CropGrowthStageService constructor.
     */
    public function __construct(CropGrowthStage $model)
    {
        parent::__construct($model);
    }

    protected function applyFilters($query, array $filters): void
    {
        if (isset($filters['crop_plan_id'])) {
            $query->where('crop_plan_id', $filters['crop_plan_id']);
        }

        if (isset($filters['recorded_by'])) {
            $query->where('recorded_by', $filters['recorded_by']);
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
