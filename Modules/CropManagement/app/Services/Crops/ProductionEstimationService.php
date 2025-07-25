<?php

namespace Modules\CropManagement\Services\Crops;

use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\CropManagement\Interfaces\Crops\ProductionEstimationInterface;
use Modules\CropManagement\Models\CropPlan;
use Modules\CropManagement\Models\ProductionEstimation;

class ProductionEstimationService implements ProductionEstimationInterface
{
   use AuthorizesRequests;
    /**
     * Summary of index
     * @return array{data: \Illuminate\Database\Eloquent\Collection<int, ProductionEstimation>, message: string}
     */
    public function  index()
    {
        $user = Auth::user();
        $this->authorize('viewAny',ProductionEstimation::class);
        $relations = [
            'cropPlan',
            'reporter'
        ];
        if ($user->hasRole('SuperAdmin')) {
            $estimations = ProductionEstimation::with($relations)
                ->orderBy('id', 'desc')->get();
        } else {
            $estimations = ProductionEstimation::with($relations)
                ->where('reported_by', $user->id)->orderBy('id', 'desc')->get();
        }
        $message = "Successfully Get All Product Estimation";
        return [
            'message' => $message,
            'data' => $estimations
        ];
    }

    /**
     * Summary of store
     * @param mixed $request
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return array{data: ProductionEstimation, message: string}
     */
    public function store($request)
    {
        try {
            $validated = $request->validated();
            $this->authorize('create',ProductionEstimation::class);
            $plan = CropPlan::findOrFail($validated['crop_plan_id']);

            if ($plan->status !== 'in-progress') {
                throw new HttpResponseException(
                    response()->json([
                        'message' => 'The crop plan is not in-progress.'
                    ], 403)
                );
            }
            $estimation = new ProductionEstimation();
            $estimation->crop_plan_id = $validated['crop_plan_id'];
            $estimation->expected_quantity = $validated['expected_quantity'];
            $estimation->setTranslations('estimation_method', $validated['estimation_method']);
            $estimation->reported_by = Auth::id();
            $estimation->save();
            return [
                'message' => 'Successfully created new production estimation.',
                'data'    => $estimation->load('cropPlan', 'reporter'),
            ];
        } catch (Exception $e) {
            Log::error('Failed to create production estimation: ' . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Summary of update
     * @param mixed $productionEstimation
     * @param mixed $request
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return array{data: mixed, message: string}
     */
    public function update($productionEstimation, $request)
    {
        try {
            $validated = $request->validated();
            $this->authorize('update',$productionEstimation);
            $plan = CropPlan::findOrFail($productionEstimation->crop_plan_id);

            if ($plan->status === 'in-progress') {
                $productionEstimation->expected_quantity = $validated['expected_quantity'];
                $productionEstimation->setTranslations('estimation_method', $validated['estimation_method']);
                $message = "Successfully updated estimation (in-progress).";
            } elseif ($plan->status === 'completed') {
                $productionEstimation->actual_quantity = $validated['actual_quantity'];
                $productionEstimation->crop_quality = $validated['crop_quality'];
                $productionEstimation->setTranslations('notes', $validated['notes']);
                $message = "Successfully updated estimation (completed plan).";
            } else {
                throw new HttpResponseException(
                    response()->json([
                        'message' => 'The crop plan must be in-progress or completed to update estimation.'
                    ], 403)
                );
            }

            $productionEstimation->save();

            return [
                'message' => $message,
                'data' => $productionEstimation->load('cropPlan', 'reporter'),
            ];
        } catch (Exception $e) {
            Log::error('Failed to update production estimation: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Summary of getProductionEstimation
     * @param mixed $productionEstimation
     * @return array{data: mixed, message: string}
     */
    public function getProductionEstimation($productionEstimation)
    {
          $this->authorize('view',$productionEstimation);
        return [
            'message' => 'Successfully Get Product Estimation ',
            'data' => $productionEstimation->load('cropPlan', 'reporter')
        ];
    }

    /**
     * Summary of destroy
     * @param mixed $productionEstimation
     * @return array{message: string}
     */
    public  function  destroy($productionEstimation)
    {
        $this->authorize('delete',$productionEstimation);
        $message = "Successfully Delete Product Estimation";
        $productionEstimation->forceDelete();
        return [
            "message" => $message
        ];
    }
}
