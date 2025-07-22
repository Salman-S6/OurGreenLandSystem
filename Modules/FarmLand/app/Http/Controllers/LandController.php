<?php

namespace Modules\FarmLand\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\FarmLand\Http\Requests\Land\StoreLandRequest;
use Modules\FarmLand\Http\Requests\Land\UpdateLandRequest;
use Modules\FarmLand\Models\Land;

class LandController extends Controller
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
    public function store(StoreLandRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Land $land)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLandRequest $request, Land $land)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Land $land)
    {
        //
    }
}
