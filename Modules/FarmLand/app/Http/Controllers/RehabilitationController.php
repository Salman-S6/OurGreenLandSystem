<?php

namespace Modules\FarmLand\Http\Controllers;

use App\http\Responses\ApiResponse;
use App\Http\Controllers\Controller;
use Modules\FarmLand\Http\Requests\Rehabilitations\StoreRehabilitationRequest;
use Modules\FarmLand\Http\Requests\Rehabilitations\UpdateRehabilitationRequest;
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
            [
                "message" => 'Rehabilitation events retrieved successfully.',
                200,
                $data
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRehabilitationRequest $request)
    {
        $rehabilitation = $this->rehabilitationService->store($request->validated());

        return ApiResponse::success(
            [
                "message" => 'Rehabilitation event created successfully.',
                201,
                $rehabilitation
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Rehabilitation $rehabilitation)
    {
        return ApiResponse::success(
            [
                200,
                $rehabilitation
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRehabilitationRequest $request, Rehabilitation $rehabilitation)
    {
        $updated = $this->rehabilitationService->update($rehabilitation, $request->validated());

        return ApiResponse::success(
            [
                "message" =>  'Rehabilitation event updated successfully.',
                200,
                $updated
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rehabilitation $rehabilitation)
    {
        $this->rehabilitationService->delete($rehabilitation);

        return ApiResponse::success(
            [
                'Rehabilitation event deleted successfully.',
                200
            ]
        );
    }
}
