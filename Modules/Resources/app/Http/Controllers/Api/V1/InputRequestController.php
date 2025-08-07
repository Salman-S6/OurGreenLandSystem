<?php

namespace Modules\Resources\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Modules\Resources\Http\Requests\InputRequest\StoreInputRequestRequest;
use Modules\Resources\Http\Requests\InputRequest\UpdateInputRequestRequest;
use Modules\Resources\Interfaces\InputRequestInterface;
use Modules\Resources\Models\InputRequest;

class InputRequestController extends Controller
{
    protected InputRequestInterface $input;

    public function __construct(InputRequestInterface $input)
    {
        $this->input = $input;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->input->getAll();
        return ApiResponse::success(
            [$data],
            "Input requests retrieved successfully.",
            200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInputRequestRequest $request)
    {
        $data = $this->input->store($request->validated());
        return ApiResponse::success(
            [$data],
            "Input request created successfully.",
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(InputRequest $inputRequest)
    {
        $data = $this->input->get($inputRequest);
        return ApiResponse::success(
            [$data],
            "Input request retrieved successfully.",
            200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInputRequestRequest $request, InputRequest $inputRequest)
    {
        $data = $this->input->update($request->validated(), $inputRequest);
        return ApiResponse::success(
            [$data],
            "Input request updated successfully.",
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InputRequest $inputRequest)
    {
        $this->input->destroy($inputRequest);
        return ApiResponse::success(
            ["deleted" => true],
            "Input request deleted successfully.",
            200
        );
    }
}
