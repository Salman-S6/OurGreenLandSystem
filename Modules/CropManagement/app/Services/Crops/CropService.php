<?php

namespace Modules\CropManagement\Services\Crops;



use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\CropManagement\Events\CropPlanDeleted;
use Modules\CropManagement\Interfaces\Crops\CropInterface;
use Modules\CropManagement\Models\Crop;

class CropService implements CropInterface
{

    use AuthorizesRequests;
    /**
     * Summary of getAll
     * @return array{data: mixed, message: string}
     */
    public function getAll()
    {
        $this->authorize('viewAny', Crop::class);
        $crops = Cache::remember('allCrops', now()->addMinutes(60), function () {
            return Crop::orderBy('id', 'desc')->get();
        });
        $data = [
            'message' => 'SuccessFully Get All Crops',
            'data' => $crops,
        ];
        return $data;
    }


    /**
     * Summary of getCrop
     * @param mixed $crop
     * @return array{crop: mixed, message: string}
     */
    public function getCrop($crop)
    {
        $this->authorize('view', $crop);
        $data = [
            'message' => 'Successfully Get Crop',
            'crop' => $crop,
        ];
        return $data;
    }


    /**
     * Summary of store
     * @param mixed $request
     * @return array{crop: Crop, message: string}
     */
    public function store($request)
    {
        try {
            $validated = $request->validated();
            $this->authorize('create', Crop::class);
            $crop = new Crop();
            $crop->setTranslations('name', $validated['name']);
            $crop->setTranslations('description', $validated['description'] ?? []);
            $crop->farmer_id = Auth::id();
            $crop->save();
            Cache::forget('allCrops');
            return [
                'message' => 'Successfully added new crop',
                'crop' => $crop
            ];
        } catch (Exception $e) {
            Log::error('Failed to add crop: ' . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Summary of update
     * @param mixed $request
     * @param mixed $crop
     * @return array{data: mixed, message: string}
     */
    public function update($request, $crop)
    {
        try {
            $validated = $request->validated();
            $this->authorize('update', $crop);
            if (isset($validated['name'])) {
                $crop->setTranslations('name', $validated['name']);
            }
            if (isset($validated['description'])) {
                $crop->setTranslations('description', $validated['description']);
            }
            $crop->save();
            Cache::forget('allCrops');
            return [
                'message' => 'Successfully updated crop',
                'data' => $crop
            ];
        } catch (Exception $e) {
            Log::error("Failed to update crop ID {$crop->id}: " . $e->getMessage());
            throw $e;
        }
    }


    /**
     * Summary of destroy
     * @param mixed $crop
     * @return array{message: string}
     */
    public function destroy($crop)
    {
        $this->authorize('delete', $crop);
        $hasUnfinishedPlans = $crop->cropPlans()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->exists();
        if ($hasUnfinishedPlans) {
            throw new HttpResponseException(response()->json(
                [
                    'message' => 'This crop cannot be deleted because it has active or in-progress plans.',
                ],
                422
            ));
        }
        DB::transaction(function () use ($crop) {
            $cropPlans = $crop->cropPlans()->get();
            foreach ($cropPlans as $cropPlan) {
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
            }
            $crop->delete();
        });
        Cache::forget('allCrops');
        return [
            'message' => 'Successfully deleted crop and all associated data.',
        ];
    }
}
