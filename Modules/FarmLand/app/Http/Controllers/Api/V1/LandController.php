<?php

namespace Modules\FarmLand\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Cache;
use Modules\FarmLand\Http\Requests\Land\StoreLandRequest;
use Modules\FarmLand\Http\Requests\Land\UpdateLandRequest;
use Modules\FarmLand\Http\Resources\LandResource;
use Modules\FarmLand\Models\Land;
use Modules\FarmLand\Services\LandService;

class LandController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected LandService $landService) {}

    /**
     * Display lands sorted by damage level (priority for rehabilitation).
     */
    public function prioritized()
    {
        $this->authorize('viewPrioritized', Land::class);

        $lands = Cache::remember('lands_prioritized', now()->addMinutes(10), function () {
            return $this->landService->getPrioritizedLands();
        });

        return ApiResponse::success([
            'data' => LandResource::collection($lands)
        ], 'Lands ordered by damage priority (high → low).', 200);
    }

    /**
     * List all lands.
     */
    public function index()
    {
        $this->authorize('viewAny', Land::class);

        $lands = Cache::remember('lands_all', now()->addMinutes(10), function () {
            return $this->landService->getAll();
        });

        return ApiResponse::success([
            'Land' => LandResource::collection($lands)
        ], 'Lands retrieved successfully.', 200);
    }

    /**
     * Store a new land record.
     */
    public function store(StoreLandRequest $request)
    {
        $this->authorize('create', Land::class);

        $land = $this->landService->store($request->validated());

        Cache::forget('lands_all');
        Cache::forget('lands_prioritized');

        return ApiResponse::success([
            'Land' => new LandResource($land)
        ], 'Land created successfully.', 201);
    }

    /**
     * Show a specific land.
     */
    public function show(Land $land)
    {
        $this->authorize('view', $land);

        $land = Cache::remember("land_show_{$land->id}", now()->addMinutes(10), function () use ($land) {
            return $this->landService->get($land);
        });

        return ApiResponse::success([
            'Land' => new LandResource($land),
        ], 'Land retrieved successfully.', 200);
    }

    /**
     * Update a land.
     */
    public function update(UpdateLandRequest $request, Land $land)
    {
        $this->authorize('update', $land);

        $updated = $this->landService->update($request->validated(), $land);

        Cache::forget('lands_all');
        Cache::forget('lands_prioritized');
        Cache::forget("land_show_{$land->id}");

        return ApiResponse::success([
            'Land' => new LandResource($updated),
        ], 'Land updated successfully.', 200);
    }

    /**
     * Delete a land.
     */
    public function destroy(Land $land)
    {
        $this->authorize('delete', $land);

        $this->landService->destroy($land);

        // Clear cache
        Cache::forget('lands_all');
        Cache::forget('lands_prioritized');
        Cache::forget("land_show_{$land->id}");

        return ApiResponse::success([
            'message' => 'Land deleted successfully.'
        ], 200);
    }
}
