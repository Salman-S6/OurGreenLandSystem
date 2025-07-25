<?php

namespace Modules\FarmLand\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Modules\FarmLand\Models\IdealAnalysisValue;
use Modules\FarmLand\Services\IdealAnalysisValue\IdealAnalysisValueService;
use Modules\FarmLand\Http\Requests\IdealAnalysisValue\StoreIdealAnalysisValueRequest;
use Modules\FarmLand\Http\Requests\IdealAnalysisValue\UpdateIdealAnalysisValueRequest;

class IdealAnalysisValueController extends Controller
{
    /**
     * Summary Of IdealAnalysisValueService.
     *
     * @var IdealAnalysisValueService
     */
    protected IdealAnalysisValueService $idealAnalysisValueService;

    /**
     * IdealAnalysisValueController Constructor.
     *
     * @param \Modules\FarmLand\Services\IdealAnalysisValue\IdealAnalysisValueService $idealAnalysisValueService
     */
    public function __construct(IdealAnalysisValueService $idealAnalysisValueService)
    {
        $this->idealAnalysisValueService = $idealAnalysisValueService;
        // $this->authorizeResource(IdealAnalysisValue::class, 'ideal_analysis_value');
    }

    /**
     *Get All Ideal Analyses Values.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {

        $data = $this->idealAnalysisValueService->getAll();
        return ApiResponse::success(
            $data,
            "SuccessFully Get All Ideal Analyses Values.",
            200
        );
    }

    /**
     * Create A New Ideal Analysis Value.
     *
     * @param \Modules\FarmLand\Http\Requests\IdealAnalysisValue\StoreIdealAnalysisValueRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreIdealAnalysisValueRequest $request): JsonResponse
    {
        try {
            $data = $this->idealAnalysisValueService->store($request);

            return ApiResponse::success(
                $data,
                "Ideal Analysis Value Created Successfully.",
                201
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }

    /**
     * Show The Specified Ideal Analysis Value.
     *
     * @param \Modules\FarmLand\Models\IdealAnalysisValue $idealAnalysisValue
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(IdealAnalysisValue $idealAnalysisValue): JsonResponse
    {
        $data = $this->idealAnalysisValueService->getIdealAnalysisValue($idealAnalysisValue);
        return ApiResponse::success(
            $data,
            "SuccessFully Get Ideal Analysis Value.",
            200
        );
    }

    /**
     * Update The Specified Ideal Analysis Value.
     *
     * @param \Modules\FarmLand\Http\Requests\IdealAnalysisValue\UpdateIdealAnalysisValueRequest $request
     * @param \Modules\FarmLand\Models\IdealAnalysisValue $idealAnalysisValue
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateIdealAnalysisValueRequest $request, IdealAnalysisValue $idealAnalysisValue): JsonResponse
    {
        try {
            $data = $this->idealAnalysisValueService->update($request, $idealAnalysisValue);
            return ApiResponse::success(
                $data,
                "Ideal Analysis Value Updated Successfully.",
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }

    /**
     * Delete The Specified Ideal Analysis Value.
     *
     * @param \Modules\FarmLand\Models\IdealAnalysisValue $idealAnalysisValue
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(IdealAnalysisValue $idealAnalysisValue): JsonResponse
    {
        try {
            $this->idealAnalysisValueService->destroy($idealAnalysisValue);
            return ApiResponse::success(
                [],
                'Ideal Analysis Value Deleted Successfully.',
                200
            );
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 500, [$e]);
        }
    }
}
