<?php

namespace Modules\CropManagement\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Interfaces\BaseCrudServiceInterface;
use Illuminate\Support\Facades\Auth;
use Modules\CropManagement\Http\Requests\Crop\StoreCropRequest;
use Modules\CropManagement\Http\Requests\Crop\UpdateCropRequest;
use Modules\CropManagement\Interfaces\Crops\CropInterface;
use Modules\CropManagement\Models\Crop;

class CropController extends Controller
{
    protected CropInterface $crop;

    public function __construct(CropInterface $crop)
    {
        $this->crop = $crop;
    }

    /**
     * Get all crops.
     */
    public function index()
    {
        $result = $this->crop->getAll();

        return ApiResponse::success(
            ['crops' => $result['data']],
             'Crops retrieved successfully.',
            200
        );
    }

    /**
     * Store a new crop.
     */
    public function store(StoreCropRequest $request)
    {
      
        $crop = $this->crop->store($request->validated());

        return ApiResponse::success(
            ['crop' => $crop],
            'Crop created successfully.',
            201
        );
    }

    /**
     * Show specific crop.
     */
    public function show(Crop $crop)
    {
        $result = $this->crop->get($crop);

        return ApiResponse::success(
            ['crop' => $result],
            'Crop retrieved successfully.',
            200
        );
    }

    /**
     * Update a crop.
     */
    public function update(UpdateCropRequest $request, Crop $crop)
    {
        $updatedCrop = $this->crop->update($request->validated(), $crop);

        return ApiResponse::success(
            ['crop' => $updatedCrop],
            'Crop updated successfully.',
            200
        );
    }

    /**
     * Delete a crop.
     */
    public function destroy(Crop $crop)
    {
        $this->crop->destroy($crop);

        return ApiResponse::success(
            ['deleted' => true],
            'Crop and all related data deleted successfully.',
            200
        );
    }
}
