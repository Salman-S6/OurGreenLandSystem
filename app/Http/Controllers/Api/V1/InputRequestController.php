<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\InputRequest\StoreInputRequestRequest;
use App\Http\Requests\InputRequest\UpdateInputRequestRequest;
use App\Models\InputRequest;

class InputRequestController extends Controller
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
    public function store(StoreInputRequestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InputRequest $inputRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInputRequestRequest $request, InputRequest $inputRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InputRequest $inputRequest)
    {
        //
    }
}
