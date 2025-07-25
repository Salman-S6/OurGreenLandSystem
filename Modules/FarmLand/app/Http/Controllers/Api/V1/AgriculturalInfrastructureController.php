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
     * @param \Modules\FarmLand\Services\AgriculturalInfrastructure\AgriculturalInfrastructureService $infrastructureService
     */
    public function __construct(AgriculturalInfrastructureService $infrastructureService)
    {
        $this->infrastructureService = $infrastructureService;
        // $this->authorizeResource(AgriculturalInfrastructure::class, 'agricultural_infrastructure');
    }

    /**
     * Get All Agricultural Infrastructure.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = $this->infrastructureService->getAll();
        return ApiResponse::success(
            $data,
            "SuccessFully Get All Agricultural Infrastructures.",
            200
        );
    }

    /**
     * Create A New Agricultural Infrastructure.
     *
     * @param \Modules\FarmLand\Http\Requests\AgriculturalInfrastructure\StoreAgriculturalInfrastructureRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreAgriculturalInfrastructureRequest $request): JsonResponse
    {
        try {
            $data = $this->infrastructureService->store($request);

            return ApiResponse::success(
                $data,
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
     * @param \Modules\FarmLand\Models\AgriculturalInfrastructure $agriculturalInfrastructure
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(AgriculturalInfrastructure $agriculturalInfrastructure): JsonResponse
    {
        $data = $this->infrastructureService->getAgricultureInfrastructure($agriculturalInfrastructure);
        return ApiResponse::success(
            $data,
            "SuccessFully Get Agricultural Infrastructure.",
            200
        );
    }

    /**
     * Update The Specified Agricultural Infrastructure.
     *
     * @param \Modules\FarmLand\Http\Requests\AgriculturalInfrastructure\UpdateAgriculturalInfrastructureRequest $request
     * @param \Modules\FarmLand\Models\AgriculturalInfrastructure $agriculturalInfrastructure
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateAgriculturalInfrastructureRequest $request, AgriculturalInfrastructure $agriculturalInfrastructure): JsonResponse
    {
        try {
            $data = $this->infrastructureService->update($request, $agriculturalInfrastructure);

            return ApiResponse::success(
                $data,
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
     * @param \Modules\FarmLand\Models\AgriculturalInfrastructure $agriculturalInfrastructure
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(AgriculturalInfrastructure $agriculturalInfrastructure): JsonResponse
    {
        try {
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
