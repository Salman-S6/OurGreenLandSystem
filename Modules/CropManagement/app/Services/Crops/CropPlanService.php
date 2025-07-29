<?php

namespace Modules\CropManagement\Services\Crops;

use App\Enums\UserRoles;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\CropManagement\Events\CropPlanCreated;
use Modules\CropManagement\Events\CropPlanDeleted;
use Modules\CropManagement\Events\CropPlanUpdated;
use Modules\CropManagement\Interfaces\Crops\CropPlanInterface;
use Modules\CropManagement\Models\CropPlan;

use App\Services\BaseCrudService;
use Illuminate\Database\Eloquent\Model;
use Modules\FarmLand\Models\Land;

class CropPlanService extends BaseCrudService implements CropPlanInterface
{
    use AuthorizesRequests;

    /**
     * Summary of __construct
     * @param \Modules\CropManagement\Models\CropPlan $model
     */
    public function __construct(CropPlan $model)
    {
        parent::__construct($model);
    }

    /**
     * Override getAll to include relations and caching & authorization
     */
    public function getAll(array $filters = []): iterable
    {
        $user = Auth::user();
        $this->authorize('viewAny', CropPlan::class);

        $relations = [
            'crop',
            'planner',
            'land',
            'productionEstimations' => fn($q) => $q->withTrashed(),
            'cropGrowthStages.pestDiseaseCases' => fn($q) => $q->withTrashed(),
            'cropGrowthStages' => fn($q) => $q->withTrashed(),
            'cropGrowthStages.bestAgriculturalPractices' => fn($q) => $q->withTrashed(),
            'cropGrowthStages.pestDiseaseCases.recommendations' => fn($q) => $q->withTrashed(),
        ];

        $cacheKey = match (true) {
            $user->hasRole(UserRoles::SuperAdmin) => 'crop_plans_all',
            $user->hasRole(UserRoles::ProgramManager) => 'crop_plans_program_manager',
            $user->hasRole(UserRoles::Farmer) => 'crop_plans_farmer_' . $user->id,
            default => 'crop_plans_user_' . $user->id
        };

        return $this->handle(function () use ($user, $relations, $cacheKey, $filters) {
            return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user, $relations, $filters) {
                $query = $this->model->newQuery()->with($relations)->orderBy('id', 'desc');


                if ($user->hasRole(UserRoles::AgriculturalAlert)) {
                    $query->where('planned_by', $user->id);
                } elseif ($user->hasRole(UserRoles::Farmer)) {
                    $query->whereHas('land', fn($q) => $q->where('farmer_id', $user->id));
                }

                foreach ($filters as $field => $value) {
                    if (!is_null($value) && $value !== '') {
                        $query->where($field, $value);
                    }
                }

                return $query->get();
            });
        });
    }




    /**
     * Override store to add validations, authorization, events, and caching
     */
    public function store(array $data): Model
    {
        return $this->handle(function () use ($data) {
            DB::beginTransaction();

            $this->authorize('create', CropPlan::class);

            $land = Land::findOrFail($data['land_id']);

            if ($land->area < $data['area_size']) {
                throw new HttpResponseException(response()->json([
                    "message" => 'Fail Make Plan For Land Because Land Area :' . $land->area .
                        ' and Plan Area is :' . $data['area_size'],
                ], 422));
            }

            $usedArea = CropPlan::where('land_id', $land->id)
                ->whereIn('status', ['active', 'in-progress'])
                ->sum('area_size');

            $availableArea = $land->area - $usedArea;

            if ($data['area_size'] > $availableArea) {
                throw new HttpResponseException(response()->json([
                    "message" => 'Fail to create crop plan: Available area is ' . $availableArea .
                        ', but the requested plan area is ' . $data['area_size'],
                ], 422));
            }

            $cropPlan = $this->model->newInstance();
            $cropPlan->land_id = $data['land_id'];
            $cropPlan->crop_id = $data['crop_id'];
            $cropPlan->planned_planting_date = $data['planned_planting_date'];
            $cropPlan->planned_harvest_date = $data['planned_harvest_date'];
            $cropPlan->seed_quantity = $data['seed_quantity'];
            $cropPlan->seed_expiry_date = $data['seed_expiry_date'];
            $cropPlan->area_size = $data['area_size'];
            $cropPlan->setTranslations('seed_type', $data['seed_type']);
            $cropPlan->planned_by = Auth::id();
            $cropPlan->save();

            Cache::forget('crop_plans_all');
            Cache::forget('crop_plans_program_manager');
            Cache::forget('crop_plans_farmer_' . $cropPlan->land->farmer_id);
            Cache::forget('crop_plans_user_' . $cropPlan->planned_by);


            event(new CropPlanCreated($cropPlan));

            DB::commit();

            return $cropPlan;
        }, "Failed to add crop plan");
    }


    /**
     * Summary of get
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return Model
     */
    public function get(Model $model): CropPlan
    {
        $this->authorize('view', $model);

        return $model->load([
            'cropGrowthStages',
            'cropGrowthStages.bestAgriculturalPractices',
            'productionEstimations',
            'cropGrowthStages.pestDiseaseCases',
            'cropGrowthStages.pestDiseaseCases.recommendations',
            'agriculturalAlerts',
            'crop',
            'planner',
            'land',
        ]);
    }

    /**
     * Override update to add validation, authorization, caching, events, and status logic
     */
    public function update(array $data, Model $model): Model
    {
        return $this->handle(function () use ($data, $model) {
            DB::beginTransaction();

            $this->authorize('update', $model);

            if (in_array($model->status, ['completed', 'cancelled'])) {
                Log::info('Trying to update a completed/cancelled crop plan');
                throw new HttpResponseException(response()->json([
                    'message' => 'You cannot update a crop plan that is already cancelled or completed.'
                ], 403));
            }

            if (isset($data['area_size'])) {
                $landId = $data['land_id'] ?? $model->land_id;
                $land = Land::findOrFail($landId);

                if ($land->area < $data['area_size']) {
                    throw new HttpResponseException(response()->json([
                        "message" => 'Fail Make Plan For Land Because Land Area: ' . $land->area .
                            ' and Plan Area is: ' . $data['area_size'],
                    ], 422));
                }

                $usedArea = CropPlan::where('land_id', $land->id)
                    ->whereIn('status', ['active', 'in-progress'])
                    ->where('id', '!=', $model->id)
                    ->sum('area_size');

                $availableArea = $land->area - $usedArea;

                if ($data['area_size'] > $availableArea) {
                    throw new HttpResponseException(response()->json([
                        "message" => 'Fail to update crop plan: Available area is ' . $availableArea .
                            ', but the requested plan area is ' . $data['area_size'],
                    ], 422));
                }

                $model->area_size = $data['area_size'];
            }

            $model->land_id = $data['land_id'] ?? $model->land_id;
            $model->crop_id = $data['crop_id'] ?? $model->crop_id;
            $model->planned_planting_date = $data['planned_planting_date'] ?? $model->planned_planting_date;
            $model->planned_harvest_date = $data['planned_harvest_date'] ?? $model->planned_harvest_date;
            $model->seed_quantity = $data['seed_quantity'] ?? $model->seed_quantity;
            $model->seed_expiry_date = $data['seed_expiry_date'] ?? $model->seed_expiry_date;

            if (isset($data['seed_type'])) {
                $model->setTranslations('seed_type', $data['seed_type']);
            }

            if (isset($data['actual_harvest_date'])) {
                $model->actual_harvest_date = $data['actual_harvest_date'];
                $model->status = 'completed';
            } elseif (isset($data['actual_planting_date'])) {
                $model->actual_planting_date = $data['actual_planting_date'];
                $model->status = 'in-progress';
            } else {
                $model->status = 'active';
            }

            $model->save();

            Cache::forget('crop_plans_all');
            Cache::forget('crop_plans_program_manager');
            Cache::forget('crop_plans_farmer_' . $model->land->farmer_id);
            Cache::forget('crop_plans_user_' . $model->planned_by);


            event(new CropPlanUpdated($model));

            DB::commit();

            return $model;
        }, "Failed to update crop plan");
    }

    /**
     * Override destroy to handle deletion logic & cascade
     */
    public function destroy(Model $model): bool
    {
        $this->authorize('delete', $model);
        return $this->handle(function () use ($model) {
            if (!in_array($model->status, ['cancelled', 'completed', 'active'])) {
                throw new HttpResponseException(response()->json([
                    'message' => 'Plan must be either active, cancelled, or completed before it can be deleted.',
                ], 403));
            }

            DB::transaction(function () use ($model) {
                if ($model->status == 'active') {
                    $model->delete();
                    return;
                }

                $model->load([
                    'cropGrowthStages' => fn($q) => $q->withTrashed(),
                    'cropGrowthStages.bestAgriculturalPractices' => fn($q) => $q->withTrashed(),
                    'productionEstimations' => fn($q) => $q->withTrashed(),
                    'cropGrowthStages.pestDiseaseCases' => fn($q) => $q->withTrashed(),
                    'cropGrowthStages.pestDiseaseCases.recommendations' => fn($q) => $q->withTrashed(),

                ]);


                foreach ($model->cropGrowthStages as $stage) {
                    $stage->bestAgriculturalPractices()->forceDelete();
                    foreach ($stage->pestDiseaseCases as $case) {
                        $case->recommendations()->forceDelete();
                        $case->forceDelete();
                    }
                    $stage->forceDelete();
                }
                $model->productionEstimations()->forceDelete();

                $model->delete();
            });

            Cache::forget('crop_plans_all');
            Cache::forget('crop_plans_program_manager');
            Cache::forget('crop_plans_farmer_' . $model->land->farmer_id);
            Cache::forget('crop_plans_user_' . $model->planned_by);

            return true;
        }, notFoundMessage: "Failed to delete crop plan");
    }

    /**
     * Summary of switchStatusToCancelled
     * @param mixed $cropPlan
     * @return array{message: string, plan: mixed}
     */
    public  function  switchStatusToCancelled($cropPlan)
    {
        try {
            $this->authorize('update', $cropPlan);
            if ($cropPlan->status == 'completed') {
                throw new HttpResponseException(response()->json([
                    'message' => 'Cant Canceled The Completed Paln',
                ], 403));
            }
            DB::transaction(function () use ($cropPlan) {
                $cropPlan->status = 'cancelled';
                $cropPlan->save();
                $cropPlan->load([
                    'cropGrowthStages',
                    'cropGrowthStages.bestAgriculturalPractices',
                    'productionEstimations',
                    'cropGrowthStages.pestDiseaseCases',
                    'cropGrowthStages.pestDiseaseCases.recommendations',
                    'agriculturalAlerts'
                ]);
                $cropPlan->productionEstimations()->delete();
                $cropPlan->agriculturalAlerts()->delete();
                foreach ($cropPlan->cropGrowthStages as $stage) {
                    $stage->bestAgriculturalPractices()->delete();
                    foreach ($stage->pestDiseaseCases as $case) {
                        $case->recommendations()->delete();
                        $case->delete();
                    }
                    $stage->delete();
                }
            });
            event(new CropPlanUpdated($cropPlan));
            Cache::forget('crop_plans_all');
            Cache::forget('crop_plans_program_manager');
            Cache::forget('crop_plans_farmer_' . $cropPlan->land->farmer_id);
            Cache::forget('crop_plans_user_' . $cropPlan->planned_by);

            return [
                'plan' => $cropPlan,
            ];
        } catch (Exception $e) {
            Log::error('Fail Switch The Status to Cancelled: ' . $e->getMessage());
            throw $e;
        }
    }
}
