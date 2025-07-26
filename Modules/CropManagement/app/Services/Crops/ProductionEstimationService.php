<?php

namespace Modules\CropManagement\Services\Crops;

use App\Enums\UserRoles;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\CropManagement\Interfaces\Crops\ProductionEstimationInterface;
use Modules\CropManagement\Models\CropPlan;
use Modules\CropManagement\Models\ProductionEstimation;
use App\Services\BaseCrudService;
use Illuminate\Database\Eloquent\Model;

class ProductionEstimationService extends BaseCrudService implements ProductionEstimationInterface
{
    use AuthorizesRequests;

    /**
     * Summary of __construct
     * @param \Modules\CropManagement\Models\ProductionEstimation $model
     */
    public function __construct(ProductionEstimation $model)
    {
        parent::__construct($model);
    }

    /**
     * Override getAll to include authorization, relations, filters and roles
     */
    public function getAll(array $filters = []): iterable
    {
        $user = Auth::user();
        $this->authorize('viewAny', ProductionEstimation::class);

        $relations = ['cropPlan', 'reporter'];

        return $this->handle(function () use ($user, $relations, $filters) {
            $query = $this->model->newQuery()->with($relations)->orderBy('id', 'desc');

            if (!$user->hasRole(UserRoles::SuperAdmin)) {
                $query->where('reported_by', $user->id);
            }


            foreach ($filters as $field => $value) {
                if ($value !== null && $value !== '') {
                    $query->where($field, $value);
                }
            }

            return $query->get();
        });
    }

    /**
     * Override get (show) to add authorization
     */
    public function getProductionEstimation($productionEstimation): Model
    {
        $this->authorize('view', $productionEstimation);
        return $productionEstimation->load('cropPlan', 'reporter');
    }

    /**
     * Override store to add validations, authorization, events, and caching
     */
    public function store(array $data): Model
    {
        return $this->handle(function () use ($data) {
            $this->authorize('create', ProductionEstimation::class);

            $plan = CropPlan::findOrFail($data['crop_plan_id']);

            if ($plan->status !== 'in-progress') {
                throw new HttpResponseException(response()->json([
                    'message' => 'The crop plan is not in-progress.'
                ], 403));
            }

            $estimation = $this->model->newInstance();
            $estimation->crop_plan_id = $data['crop_plan_id'];
            $estimation->expected_quantity = $data['expected_quantity'];
            $estimation->setTranslations('estimation_method', $data['estimation_method']);
            $estimation->reported_by = Auth::id();
            $estimation->save();

            return $estimation->load('cropPlan', 'reporter');
        }, 'Failed to create production estimation');
    }

    /**
     * Override update to add validation and authorization
     */
    public function update(array $data, Model $model): Model
    {
        return $this->handle(function () use ($data, $model) {
            $this->authorize('update', $model);

            $plan = CropPlan::findOrFail($model->crop_plan_id);

            if ($plan->status === 'in-progress') {
                $model->expected_quantity = $data['expected_quantity'] ?? $model->expected_quantity;
                if (isset($data['estimation_method'])) {
                    $model->setTranslations('estimation_method', $data['estimation_method']);
                }
            } elseif ($plan->status === 'completed') {
                $model->actual_quantity = $data['actual_quantity'] ?? $model->actual_quantity;
                $model->crop_quality = $data['crop_quality'] ?? $model->crop_quality;
                if (isset($data['notes'])) {
                    $model->setTranslations('notes', $data['notes']);
                }
            } else {
                throw new HttpResponseException(response()->json([
                    'message' => 'The crop plan must be in-progress or completed to update estimation.'
                ], 403));
            }

            $model->save();

            return $model->load('cropPlan', 'reporter');
        }, 'Failed to update production estimation');
    }

    /**
     * Override destroy with authorization
     */
    public function destroy(Model $model): bool
    {
        $this->authorize('delete', $model);
        return $this->handle(function () use ($model) {
            return $model->forceDelete();
        }, 'Failed to delete production estimation');
    }
}
