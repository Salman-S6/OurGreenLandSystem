<?php

namespace App\Http\Controllers\Api\V1\CropManagement;

use App\Http\Controllers\Controller;

use App\Http\Requests\Crop\StoreCropRequest;
use App\Http\Requests\Crop\UpdateCropRequest;
use App\Models\Crop;

class CropController extends Controller
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
    public function store(StoreCropRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Crop $crop)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropRequest $request, Crop $crop)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Crop $crop)
    {
        //
    }
}
