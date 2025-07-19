<?php

namespace App\Http\Controllers\Api\V1\Resources;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierRating\StoreSupplierRatingRequest;
use App\Http\Requests\SupplierRating\UpdateSupplierRatingRequest;
use App\Models\SupplierRating;

class SupplierRatingController extends Controller
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
    public function store(StoreSupplierRatingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplierRating $supplierRating)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRatingRequest $request, SupplierRating $supplierRating)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupplierRating $supplierRating)
    {
        //
    }
}
