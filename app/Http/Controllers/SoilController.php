<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSoilRequest;
use App\Http\Requests\UpdateSoilRequest;
use App\Models\Soil;

class SoilController extends Controller
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
    public function store(StoreSoilRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Soil $soil)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSoilRequest $request, Soil $soil)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Soil $soil)
    {
        //
    }
}
