<?php

namespace Modules\FarmLand\Http\Controllers;

use App\http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Modules\FarmLand\Http\Requests\Rehabilitations\StoreRehabilitationRequest;
use Modules\FarmLand\Http\Requests\Rehabilitations\UpdateRehabilitationRequest;
use Modules\FarmLand\Http\Resources\RehabilitationResource;
use Modules\FarmLand\Models\Rehabilitation;
 
use Modules\FarmLand\Services\RehabilitationService;

class RehabilitationController extends Controller
{

    protected $rehabilitationService;

    public function __construct(RehabilitationService $rehabilitationService)
    {
        $this->rehabilitationService = $rehabilitationService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->rehabilitationService->getAll();
    return ApiResponse::success(
        ['rehabilitations' => RehabilitationResource::collection($data)],
        'Rehabilitation events retrieved successfully.',
        200
    );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRehabilitationRequest $request)
    {
        $rehabilitation = $this->rehabilitationService->store($request->validated());

    return ApiResponse::success(
        ['rehabilitations' => new RehabilitationResource($rehabilitation)],
        'Rehabilitation events created successfully.',
        201
    );
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
          $rehabilitation = $this->rehabilitationService->getById($id);
     return ApiResponse::success(
[       ' rehabilitations' => $rehabilitation ,
     ],  'Rehabilitation event retrieved successfully.',
        200
    );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRehabilitationRequest $request, Rehabilitation $rehabilitation)
    {
        $updated = $this->rehabilitationService->update($rehabilitation, $request->validated());

     return ApiResponse::success(
        ['rehabilitations' => new RehabilitationResource($updated)],
        'Rehabilitation event updated successfully.',
        200
    );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rehabilitation $rehabilitation)
    {
        $this->rehabilitationService->delete($rehabilitation);

        return ApiResponse::success(
           
        ["message"=> 'Rehabilitation event deleted successfully.']
        );
    }
}