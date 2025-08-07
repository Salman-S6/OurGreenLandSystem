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
     * @param IdealAnalysisValueService $idealAnalysisValueService
     */
    public function __construct(IdealAnalysisValueService $idealAnalysisValueService)
    {
        $this->idealAnalysisValueService = $idealAnalysisValueService;
    }

    /**
     *Get All Ideal Analyses Values.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        // $this->authorize('viewAny', IdealAnalysisValue::class);
        $data = $this->idealAnalysisValueService->getAll();
        return ApiResponse::success(
            [$data],
            "SuccessFully Get All Ideal Analyses Values.",
            200
        );
    }

    /**
     * Create A New Ideal Analysis Value.
     *
     * @param StoreIdealAnalysisValueRequest $request
     * @return JsonResponse
     */
    public function store(StoreIdealAnalysisValueRequest $request): JsonResponse
    {
        try {
            // $this->authorize('create', IdealAnalysisValue::class);
            $data = $this->idealAnalysisValueService->store($request->validated());

            return ApiResponse::success(
                [$data],
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
     * @param IdealAnalysisValue $idealAnalysisValue
     * @return JsonResponse
     */
    public function show(IdealAnalysisValue $idealAnalysisValue): JsonResponse
    {
        // $this->authorize('view', $idealAnalysisValue);
        $data = $this->idealAnalysisValueService->get($idealAnalysisValue);
        return ApiResponse::success(
            [$data],
            "SuccessFully Get Ideal Analysis Value.",
            200
        );
    }

    /**
     * Update The Specified Ideal Analysis Value.
     *
     * @param UpdateIdealAnalysisValueRequest $request
     * @param IdealAnalysisValue $idealAnalysisValue
     * @return JsonResponse
     */
    public function update(UpdateIdealAnalysisValueRequest $request, IdealAnalysisValue $idealAnalysisValue): JsonResponse
    {
        try {
            // $this->authorize('update', $idealAnalysisValue);
            $data = $this->idealAnalysisValueService->update($request->validated(), $idealAnalysisValue);
            return ApiResponse::success(
                [$data],
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
     * @param IdealAnalysisValue $idealAnalysisValue
     * @return JsonResponse
     */
    public function destroy(IdealAnalysisValue $idealAnalysisValue): JsonResponse
    {
        try {
            // $this->authorize('delete', $idealAnalysisValue);
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
