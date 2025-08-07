<?php

namespace Modules\FarmLand\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Modules\FarmLand\Models\AgriculturalInfrastructure;
use Modules\FarmLand\Services\AgriculturalInfrastructure\AgriculturalInfrastructureService;
use Modules\FarmLand\Http\Requests\AgriculturalInfrastructure\StoreAgriculturalInfrastructureRequest;
use Modules\FarmLand\Http\Requests\AgriculturalInfrastructure\UpdateAgriculturalInfrastructureRequest;

class AgriculturalInfrastructureController extends Controller
{
    /**
     * Summary of AgriculturalInfrastructureService.
     *
     * @var AgriculturalInfrastructureService
     */
    protected AgriculturalInfrastructureService $infrastructureService;

    /**
     * AgriculturalInfrastructureController Constructor.
     *
     * @param AgriculturalInfrastructureService $infrastructureService
     */
    public function __construct(AgriculturalInfrastructureService $infrastructureService)
    {
        $this->infrastructureService = $infrastructureService;
    }

    /**
     * Get All Agricultural Infrastructure.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // $this->authorize('viewAny', AgriculturalInfrastructure::class);
        $data = $this->infrastructureService->getAll();
        return ApiResponse::success(
            [$data],
            "SuccessFully Get All Agricultural Infrastructures.",
            200
        );
    }

    /**
     * Create A New Agricultural Infrastructure.
     *
     * @param StoreAgriculturalInfrastructureRequest $request
     * @return JsonResponse
     */
    public function store(StoreAgriculturalInfrastructureRequest $request): JsonResponse
    {
        try {
            // $this->authorize('create', AgriculturalInfrastructure::class);
            $data = $this->infrastructureService->store($request->validated());

            return ApiResponse::success(
                [$data],
                "Agricultural Infrastructure Created Successfully.",
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }

    /**
     * Show The Specified Agricultural Infrastructure.
     *
     * @param AgriculturalInfrastructure $agriculturalInfrastructure
     * @return JsonResponse
     */
    public function show(AgriculturalInfrastructure $agriculturalInfrastructure): JsonResponse
    {
        // $this->authorize('view', $agriculturalInfrastructure);
        $data = $this->infrastructureService->get($agriculturalInfrastructure);
        return ApiResponse::success(
            [$data],
            "SuccessFully Get Agricultural Infrastructure.",
            200
        );
    }

    /**
     * Update The Specified Agricultural Infrastructure.
     *
     * @param UpdateAgriculturalInfrastructureRequest $request
     * @param AgriculturalInfrastructure $agriculturalInfrastructure
     * @return JsonResponse
     */
    public function update(UpdateAgriculturalInfrastructureRequest $request, AgriculturalInfrastructure $agriculturalInfrastructure): JsonResponse
    {
        try {
            // $this->authorize('update', $agriculturalInfrastructure);
            $data = $this->infrastructureService->update($request->validated(), $agriculturalInfrastructure);

            return ApiResponse::success(
                [$data],
                "Agricultural Infrastructure Updated Successfully.",
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }

    /**
     * Delete The Specified Agricultural Infrastructure.
     *
     * @param AgriculturalInfrastructure $agriculturalInfrastructure
     * @return JsonResponse
     */
    public function destroy(AgriculturalInfrastructure $agriculturalInfrastructure): JsonResponse
    {
        try {
            // $this->authorize('delete', $agriculturalInfrastructure);
            $this->infrastructureService->destroy($agriculturalInfrastructure);
            return ApiResponse::success(
                [],
                'Agricultural Infrastructure Deleted Successfully.',
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }
}
