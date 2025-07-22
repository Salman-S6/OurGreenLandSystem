<?php

namespace Modules\CropManagement\Services\Crops;



use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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
            Log::error('Failed to update crop: ' . $e->getMessage());
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
            throw  new HttpResponseException(response()->json(
                [
                    'message' => 'Cannot delete the crop. There are active or in-progress plans associated with it.',
                ],
                422
            ));
        }
        $message = "successfully Delete Crop";
        $crop->delete();
        cache::forget('allCrops');
        return [
            'message' => $message,
        ];
    }
}
