<?php

namespace Modules\FarmLand\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Modules\FarmLand\Http\Requests\Land\StoreLandRequest;
use Modules\FarmLand\Http\Requests\Land\UpdateLandRequest;
use Modules\FarmLand\Http\Resources\LandResource;
use Modules\FarmLand\Models\Land;
use Modules\FarmLand\Services\LandService;

class LandController extends Controller
{
    public function __construct(protected LandService $landService) {}

    /**
     * Display lands sorted by damage level (priority for rehabilitation).
     */
    public function prioritized()
    {
        $lands = $this->landService->getPrioritizedLands();

        return ApiResponse::success([
            'data' =>LandResource::collection($lands)] , 'Lands ordered by damage priority (high → low).', 200);
    }

    /**
     * List all lands.
     */
    public function index()
    {
        $lands = $this->landService->getAllLands();
        return ApiResponse::success(
            ["Land" =>LandResource::collection($lands)],
            'Lands retrieved successfully.',

            200
        );
    }

    /**
     * Store a new land record.
     */
    public function store(StoreLandRequest $request)
    {
        $land = $this->landService->store($request->validated());
        return ApiResponse::success(
            ["Land" => new LandResource($land)],
            'Land Created successfully.',
            201
        ); 
    }

    /**
     * Show a specific land.
     */
    public function show(string $id)
    {
        $land = $this->landService->show($id);
        return ApiResponse::success(
            ["Land" =>   $land],
            'land retrieved successfully.',
            200
        );
    }

    /**
     * Update a land.
     */
    public function update(UpdateLandRequest $request, Land $land)
    {
        $updated = $this->landService->update($request->validated(), $land);
        return ApiResponse::success(
            ["Land" => new LandResource($updated),],
            'Land updated successfully.',
            200
        );
    }

    /**
     * Delete a land.
     */
    public function destroy(Land $land)
    {
        $this->landService->destroy($land);
        return ApiResponse::success(['message' => 'Land deleted successfully.'], 200);
    }
}
