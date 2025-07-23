<?php

namespace Modules\FarmLand\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Modules\FarmLand\Http\Requests\Land\StoreLandRequest;
use Modules\FarmLand\Http\Requests\Land\UpdateLandRequest;
use Modules\FarmLand\Models\Land;
use Modules\FarmLand\Services\LandService;

class LandController extends Controller
{
  public function __construct(protected LandService $landService) {}

    /**
     * List all lands.
     */
    public function index()
    {
        $lands = $this->landService->getAllLands();
        return ApiResponse::success(
            [
                "message" => 'Lands retrieved successfully.',
                200,
                $lands
            ]
        );
    }

    /**
     * Store a new land record.
     */
    public function store(StoreLandRequest $request) 
    {
        $land = $this->landService->store($request->validated());
        return ApiResponse::success(
            [
                "message" => 'Land Created successfully.',
                201,
                $land
            ]
        );
    }

    /**
     * Show a specific land.
     */
    public function show(int $id) 
    {
        $land = $this->landService->show($id);
         return ApiResponse::success(
            [
                "message" => 'land retrieved successfully.',
                200,
                $land
            ]
        );
    }

    /**
     * Update a land.
     */
    public function update(UpdateLandRequest $request, Land $land) 
    {
        $updated = $this->landService->update($request->validated(),$land);
                return ApiResponse::success(
            [
                "message" => 'Land updated successfully.',
                200,
                $updated
            ]
        );
    }

    /**
     * Delete a land.
     */
    public function destroy(Land $land)
    {
        $this->landService->destroy($land);
        return response()->json(['message' => 'Land deleted successfully.']);
    }
}