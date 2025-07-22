<?php

namespace Modules\FarmLand\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rehabilitations\StoreRehabilitationRequest;
use App\Http\Requests\Rehabilitations\UpdateRehabilitationRequest;
use Modules\FarmLand\Models\Rehabilitation;

class RehabilitationController extends Controller
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
    public function store(StoreRehabilitationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Rehabilitation $rehabilitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRehabilitationRequest $request, Rehabilitation $rehabilitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rehabilitation $rehabilitation)
    {
        //
    }
}
