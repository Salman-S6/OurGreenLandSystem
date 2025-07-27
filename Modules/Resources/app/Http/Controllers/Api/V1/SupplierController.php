<?php

namespace  Modules\Resources\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Modules\Resources\Http\Requests\Supplier\StoreSupplierRequest;
use Modules\Resources\Http\Requests\Supplier\UpdateSupplierRequest;
use Modules\Resources\Models\Supplier;
use Modules\Resources\Services\SupplierService;
use Modules\Resources\Http\Resources\SupplierResource;

class SupplierController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        protected SupplierService $service
    ) {}

    /**
     * Display a listing of the suppliers.
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Supplier::class);
        $suppliers = $this->service->getAll();
        return ApiResponse::success(
            ["Suppliers"=>SupplierResource::collection($suppliers)],
            "Supplier retrived successfully",200);
    }

    /**
     * Store a newly created supplier.
     */
    public function store(StoreSupplierRequest $request): JsonResponse
    {   
        $this->authorize('create', Supplier::class);
        $supplier = $this->service->store($request->validated());
        return ApiResponse::success(["Supplier"=>new SupplierResource($supplier)],"Supplier   Created Successfully", 201);
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier): JsonResponse
    {
        $this->authorize('view', $supplier);
        $supplier = $this->service->get($supplier);
        return ApiResponse::success(
            ["Supplier" => new SupplierResource($supplier)],
            "Supplier retrieved successfully",
            200
        );
    }

    /**
     * Update the specified supplier.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier): JsonResponse
    {
                $this->authorize('update', $supplier);
        $supplier = $this->service->update( $request->validated(),$supplier);
        return ApiResponse::success(
            ["Supplier" => new SupplierResource($supplier)],
            "Supplier updated successfully",
            200
        );
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(Supplier $supplier): JsonResponse
    {
         $this->authorize('delete', $supplier);
        $this->service->destroy($supplier);
        return ApiResponse::success([null],"Supplier deleted successfully",200);
    }
}
