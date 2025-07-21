<?php

namespace Modules\CropManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CropManagement\Http\Requests\PestDiseaseCase\StorePestDiseaseCaseRequest;
use Modules\CropManagement\Http\Requests\PestDiseaseCase\UpdatePestDiseaseCaseRequest;
use Modules\CropManagement\Models\PestDiseaseCase;

class PestDiseaseCaseController extends Controller
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
    public function store(StorePestDiseaseCaseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PestDiseaseCase $pestDiseaseCase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePestDiseaseCaseRequest $request, PestDiseaseCase $pestDiseaseCase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PestDiseaseCase $pestDiseaseCase)
    {
        //
    }
}
