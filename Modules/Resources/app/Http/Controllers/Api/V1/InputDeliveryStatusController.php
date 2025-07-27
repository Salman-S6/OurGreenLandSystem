<?php

namespace  Modules\Resources\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\InputDeliveryStatus\StoreInputDeliveryStatusRequest;
use App\Http\Requests\InputDeliveryStatus\UpdateInputDeliveryStatusRequest;
use App\Models\InputDeliveryStatus;

class InputDeliveryStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInputDeliveryStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InputDeliveryStatus $inputDeliveryStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInputDeliveryStatusRequest $request, InputDeliveryStatus $inputDeliveryStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InputDeliveryStatus $inputDeliveryStatus)
    {
        //
    }
}
