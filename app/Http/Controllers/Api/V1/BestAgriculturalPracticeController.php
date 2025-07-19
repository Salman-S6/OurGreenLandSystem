<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Http\Requests\BestAgriculturalPractice\StoreBestAgriculturalPracticeRequest;
use App\Http\Requests\BestAgriculturalPractice\UpdateBestAgriculturalPracticeRequest;
use App\Models\BestAgriculturalPractice;

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
