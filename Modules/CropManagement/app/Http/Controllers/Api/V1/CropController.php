<?php

namespace Modules\CropManagement\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Http\Request;
use Modules\CropManagement\Http\Requests\Crop\StoreCropRequest;
use Modules\CropManagement\Http\Requests\Crop\UpdateCropRequest;
use Modules\CropManagement\Models\Crop;

class CropController extends Controller
{

    protected $crop;


    /**
     * Summary of __construct
     * @param \App\Interfaces\Crops\CropInterface $crop
     */
    public  function __construct(CropInterface $crop)
    {
        $this->crop = $crop;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->crop->getAll();
        return ApiResponse::success(
            [$data['data']],
            $data['message'],
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCropRequest $request)
    {
        $data = $this->crop->store($request);

        return ApiResponse::success(
            ['crop' => $data['crop']],
            $data['message'],
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Crop $crop)
    {
        $data = $this->crop->getCrop($crop);
        return ApiResponse::success(
            [$data['crop']],
            $data['message'],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropRequest $request, Crop $crop)
    {
        $data = $this->crop->update($request, $crop);
        return ApiResponse::success(
            [$data['data']],
            $data['message'],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Crop $crop)
    {
        $data = $this->crop->destroy($crop);
        return ApiResponse::success(
            ['deleted' => true],
            $data['message'],
            200
        );
    }
}
