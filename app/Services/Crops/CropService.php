<?php

namespace App\Services\Crops;

use App\Interfaces\Crops\CropInterface;
use App\Models\Crop;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

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
            $validate = $request->validated();
            $this->authorize('create', Crop::class);
            $crop = new Crop();
            $crop->name = $validate['name'];
            $crop->description = $validate['description'];
            $crop->farmer_id = Auth::user()->id;
            $crop->save();
            Cache::forget('allCrops');
            $message = "Successfully add new Crop";
            $data = [
                'message' => $message,
                'crop' => $crop
            ];
            return $data;
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
            $validate = $request->validated();
            $this->authorize('update', $crop);
            $crop->update($validate);
            Cache::forget('allCrops');
            $message = "successfully Update Crop";
            $data = [
                'message' => $message,
                'data' => $crop
            ];
            return $data;
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
