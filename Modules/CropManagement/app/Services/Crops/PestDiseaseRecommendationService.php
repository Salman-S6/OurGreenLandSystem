<?php

namespace Modules\CropManagement\Services\Crops;

use App\Services\BaseCrudService;
use Modules\CropManagement\Models\PestDiseaseRecommendation;
use Illuminate\Database\Eloquent\Model;

class PestDiseaseRecommendationService extends BaseCrudService
{
    /**
     * CropGrowthStageService constructor.
     */
    public function __construct(PestDiseaseRecommendation $model)
    {
        parent::__construct($model);
    }

    protected function applyFilters($query, array $filters): void
    {
        if (isset($filters['pest_disease_case_id'])) {
            $query->where('pest_disease_case_id', $filters['pest_disease_case_id']);
        }

        if (isset($filters['recommended_by'])) {
            $query->where('recommended_by', $filters['recommended_by']);
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
