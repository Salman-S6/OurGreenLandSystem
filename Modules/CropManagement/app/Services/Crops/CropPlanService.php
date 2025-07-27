<?php

namespace Modules\CropManagement\Services\Crops;


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
use Modules\Farmland\Models\Land;

class CropPlanService implements CropPlanInterface
{

    use AuthorizesRequests;




    /**
     * Summary of index
     * @return array{message: string, plans: mixed}
     */
    public function index()
    {
        $user = Auth::user();
        $this->authorize('viewAny', CropPlan::class);

        $relations = [
            'crop',
            'planner',
            'land',
            'productionEstimations' => fn($q) => $q->withTrashed(),
            'pestDiseaseCases' => fn($q) => $q->withTrashed(),
            'cropGrowthStages' => fn($q) => $q->withTrashed(),
        ];
        $cacheKey = $user->hasRole('SuperAdmin')
            ? 'crop_plans_all'
            : 'crop_plans_user_' . $user->id;
        $plans = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user, $relations) {
            $query = CropPlan::with($relations)->orderBy('id', 'desc');

            if (!$user->hasRole('SuperAdmin')) {
                $query->where('planned_by', $user->id);
            }
            return $query->get();
        });

        return [
            'message' => 'Successfully Get Plans',
            'plans' => $plans,
        ];
    }


    /**
     * Summary of store
     * @param mixed $request
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return array{message: string, plan: CropPlan}
     */
    public function store($request)
    {
        try {
            DB::beginTransaction();

            $validate = $request->validated();

            $this->authorize('create', CropPlan::class);

            $land = Land::findOrFail($validate['land_id']);

            if ($land->area < $validate['area_size']) {
                throw new HttpResponseException(response()->json([
                    "message" => 'Fail Make Plan For Land Because Land Area :' . $land->area .
                        ' and Plan Area is :' . $validate['area_size'],
                ], 422));
            }

            $usedArea = CropPlan::where('land_id', $land->id)
                ->whereIn('status', ['active', 'in-progress'])
                ->sum('area_size');

            $availableArea = $land->area - $usedArea;

            if ($validate['area_size'] > $availableArea) {
                throw new HttpResponseException(response()->json([
                    "message" => 'Fail to create crop plan: Available area is ' . $availableArea .
                        ', but the requested plan area is ' . $validate['area_size'],
                ], 422));
            }

            $cropPlan = new CropPlan();
            $cropPlan->land_id = $validate['land_id'];
            $cropPlan->crop_id = $validate['crop_id'];
            $cropPlan->planned_planting_date = $validate['planned_planting_date'];
            $cropPlan->planned_harvest_date = $validate['planned_harvest_date'];
            $cropPlan->seed_quantity = $validate['seed_quantity'];
            $cropPlan->seed_expiry_date = $validate['seed_expiry_date'];
            $cropPlan->area_size = $validate['area_size'];
            $cropPlan->setTranslations('seed_type', $validate['seed_type']);
            $cropPlan->planned_by = Auth::id();
            $cropPlan->save();
            Cache::forget('crop_plans_all');
            Cache::forget('crop_plans_user_' . Auth::id());

            event(new CropPlanCreated($cropPlan));

            DB::commit();

            return [
                'message' => "Successfully Add Plan To Land " . $land->id,
                'plan' => $cropPlan,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Failed to add crop plan to land ID {$validate['land_id']}: " . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Summary of show
     * @param mixed $cropPlan
     * @return array{message: string, plan: mixed}
     */
    public function show($cropPlan)
    {
        $this->authorize('view', $cropPlan);
        $message = "Successfully Get Plan";
        return [
            'message' => $message,
            'plan' => $cropPlan
        ];
    }

    /**
     * Summary of update
     * @param mixed $request
     * @param mixed $cropPlan
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     *
     */
    public function update($request, $cropPlan)
    {
        try {
            DB::beginTransaction();
            $validate = $request->validated();
            $this->authorize('update', $cropPlan);
            if (in_array($cropPlan->status, ['completed', 'cancelled'])) {
                return response()->json([
                    'message' => 'You cannot update a crop plan that is already cancelled or completed.'
                ], 403);
            }
            if (isset($validate['area_size'])) {
                $landId = $validate['land_id'] ?? $cropPlan->land_id;
                $land = Land::findOrFail($landId);
                if ($land->area < $validate['area_size']) {
                    throw new HttpResponseException(response()->json([
                        "message" => 'Fail Make Plan For Land Because Land Area: ' . $land->area .
                            ' and Plan Area is: ' . $validate['area_size'],
                    ], 422));
                }
                $usedArea = CropPlan::where('land_id', $land->id)
                    ->whereIn('status', ['active', 'in-progress'])
                    ->where('id', '!=', $cropPlan->id)
                    ->sum('area_size');
                $availableArea = $land->area - $usedArea;
                if ($validate['area_size'] > $availableArea) {
                    throw new HttpResponseException(response()->json([
                        "message" => 'Fail to update crop plan: Available area is ' . $availableArea .
                            ', but the requested plan area is ' . $validate['area_size'],
                    ], 422));
                }

                $cropPlan->area_size = $validate['area_size'];
            }
            $cropPlan->land_id = $validate['land_id'] ?? $cropPlan->land_id;
            $cropPlan->crop_id = $validate['crop_id'] ?? $cropPlan->crop_id;
            $cropPlan->planned_planting_date = $validate['planned_planting_date'] ?? $cropPlan->planned_planting_date;
            $cropPlan->planned_harvest_date = $validate['planned_harvest_date'] ?? $cropPlan->planned_harvest_date;
            $cropPlan->seed_quantity = $validate['seed_quantity'] ?? $cropPlan->seed_quantity;
            $cropPlan->seed_expiry_date = $validate['seed_expiry_date'] ?? $cropPlan->seed_expiry_date;
            if (isset($validate['seed_type'])) {
                $cropPlan->setTranslations('seed_type', $validate['seed_type']);
            }
            if (isset($validate['actual_harvest_date'])) {
                $cropPlan->actual_harvest_date = $validate['actual_harvest_date'];
                $cropPlan->status = 'completed';
            } elseif (isset($validate['actual_planting_date'])) {
                $cropPlan->actual_planting_date = $validate['actual_planting_date'];
                $cropPlan->status = 'in-progress';
            } else {
                $cropPlan->status = 'active';
            }
            $cropPlan->save();
            Cache::forget('crop_plans_all');
            Cache::forget('crop_plans_user_' . Auth::id());
            event(new CropPlanUpdated($cropPlan));
            DB::commit();
            return [
                'message' => 'Crop plan updated successfully',
                'plan' => $cropPlan,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Fail Update Plan: ' . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Summary of destroy
     * @param mixed $cropPlan
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return array{message: string}
     */
    public function destroy($cropPlan)
    {
        if (in_array($cropPlan->status, ['cancelled', 'completed', 'active'])) {
            DB::transaction(function () use ($cropPlan) {
                if ($cropPlan->status === 'active') {
                    $cropPlan->delete();
                    return;
                }
                $cropPlan->load([
                    'cropGrowthStages' => fn($q) => $q->withTrashed(),
                    'cropGrowthStages.bestAgriculturalPractices' => fn($q) => $q->withTrashed(),
                    'productionEstimations' => fn($q) => $q->withTrashed(),
                    'pestDiseaseCases' => fn($q) => $q->withTrashed(),
                    'pestDiseaseCases.recommendations' => fn($q) => $q->withTrashed(),
                ]);
                event(new CropPlanDeleted($cropPlan));

                foreach ($cropPlan->cropGrowthStages as $stage) {
                    $stage->bestAgriculturalPractices()->forceDelete();
                    $stage->forceDelete();
                }
                $cropPlan->productionEstimations()->forceDelete();

                foreach ($cropPlan->pestDiseaseCases as $case) {
                    $case->recommendations()->forceDelete();
                    $case->forceDelete();
                }

                $cropPlan->delete();
            });
            Cache::forget('crop_plans_all');
            Cache::forget('crop_plans_user_' . Auth::id());
            return [
                'message' => "Successfully deleted crop plan",
            ];
        } else {
            throw new HttpResponseException(
                response()->json([
                    'message' => 'Plan must be either active, cancelled, or completed before it can be deleted.',
                ], 403)
            );
        }
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
            DB::transaction(function () use ($cropPlan) {
                $cropPlan->status = 'cancelled';
                $cropPlan->save();
                $cropPlan->productionEstimations()->delete();
                foreach ($cropPlan->pestDiseaseCases as $case) {
                    $case->recommendations()->delete();
                    $case->delete();
                }
                $cropPlan->agriculturalAlerts()->delete();
                foreach ($cropPlan->cropGrowthStages as $stage) {
                    $stage->bestAgriculturalPractices()->delete();
                    $stage->delete();
                }
            });
            Cache::forget('crop_plans_all');
            Cache::forget('crop_plans_user_' . Auth::id());

            return [
                'message' => 'Successfully Changed Status To Cancelled',
                'plan' => $cropPlan->fresh(),
            ];
        } catch (Exception $e) {
            Log::error('Fail Switch The Status to Cancelled: ' . $e->getMessage());
            throw $e;
        }
    }
}
