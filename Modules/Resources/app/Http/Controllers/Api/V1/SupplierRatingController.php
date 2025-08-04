<?php

namespace  Modules\Resources\Http\Controllers\Api\V1;

use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Resources\Http\Requests\SupplierRating\StoreSupplierRatingRequest;
use Modules\Resources\Http\Requests\SupplierRating\UpdateSupplierRatingRequest;
use Modules\Resources\Models\SupplierRating;

use Modules\Resources\Services\SupplierRatingService;

class SupplierRatingController extends Controller
{
        use AuthorizesRequests;
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
    public function show(SupplierRating $rating): JsonResponse
    {
                $this->authorize('viewAny', SupplierRating::class);
        $rating = $this->service->get($rating);

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
    public function update(UpdateSupplierRatingRequest $request, SupplierRating $rating): JsonResponse
    {

         $this->authorize('update', $rating);
        $data = $request->validated();

        $rating = $this->service->update($data, $rating);

        return ApiResponse::success(['data' => $rating]);
    }

    /**        
     * Remove the specified supplier rating.
     */
  
public function destroy(SupplierRating $rating)
{
            //  $this->authorize('delete', $rating);
        $this->service->destroy($rating);
        return ApiResponse::success(["Supplier rating deleted successfully"]);
    
    }

}
