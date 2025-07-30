<?php

namespace Modules\Resources\Http\Controllers;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Resources\Http\Requests\SupplierRating\StoreSupplierRatingRequest;
use Modules\Resources\Http\Requests\SupplierRating\UpdateSupplierRatingRequest;
use Modules\Resources\Models\SupplierRating;

use Modules\Resources\Services\SupplierRatingService;

class SupplierRatingController extends Controller
{
    protected SupplierRatingService $service;

    public function __construct(SupplierRatingService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of supplier ratings.
     */
    public function index(): JsonResponse
    {
                $this->authorize('viewAny', SupplierRating::class);
        $ratings = $this->service->getAll();

        return ApiResponse::success(['data' => $ratings],200);
    }

    /**
     * Display a specific SupplierRating rating.
     */
    public function show(SupplierRating $supplierRating): JsonResponse
    {
                $this->authorize('viewAny', SupplierRating::class);
        $rating = $this->service->get($supplierRating);

        return ApiResponse::success(['data' => $rating]);
    }

    /**
     * Store a newly created supplier rating.
     */
    public function store(StoreSupplierRatingRequest $request): JsonResponse
    {
                $this->authorize('create', SupplierRating::class);
        $data = $request->validated();


        $data['reviewer_id'] = Auth::user()->id;

        $rating = $this->service->store($data);

        return ApiResponse::success(['data' => $rating],"New suplier rating created", 201);
    }

    /**
     * Update the specified supplier rating.
     */
    public function update(UpdateSupplierRatingRequest $request, SupplierRating $supplierRating): JsonResponse
    {

         $this->authorize('update', $supplierRating);
        $data = $request->validated();

        $rating = $this->service->update($data, $supplierRating);

        return ApiResponse::success(['data' => $rating]);
    }

    /**        
     * Remove the specified supplier rating.
     */
    public function destroy(SupplierRating $supplierRating): JsonResponse
    {
        $this->authorize('delete', $supplierRating);
        $this->service->destroy($supplierRating);

        return ApiResponse::success([null],"Supplier rating deleted successfully",204);
    }
}
