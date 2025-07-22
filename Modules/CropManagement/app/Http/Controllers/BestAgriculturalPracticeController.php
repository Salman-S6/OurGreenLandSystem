<?php

namespace Modules\CropManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\CropManagement\Http\Requests\BestAgriculturalPractice\StoreBestAgriculturalPracticeRequest;
use Modules\CropManagement\Http\Requests\BestAgriculturalPractice\UpdateBestAgriculturalPracticeRequest;
use Modules\CropManagement\Models\BestAgriculturalPractice;

class BestAgriculturalPracticeController extends Controller
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
    public function store(StoreBestAgriculturalPracticeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(BestAgriculturalPractice $bestAgriculturalPractices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBestAgriculturalPracticeRequest $request, BestAgriculturalPractice $bestAgriculturalPractices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BestAgriculturalPractice $bestAgriculturalPractices)
    {
        //
    }
}
