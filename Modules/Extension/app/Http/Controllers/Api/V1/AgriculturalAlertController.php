<?php

namespace Modules\Extension\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Extension\Http\Requests\AgriculturalAlert\StoreAgriculturalAlertRequest;
use Modules\Extension\Http\Requests\AgriculturalAlert\UpdateAgriculturalAlertRequest;
use Modules\Extension\Models\AgriculturalAlert;

class AgriculturalAlertController extends Controller
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
    public function store(StoreAgriculturalAlertRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AgriculturalAlert $agriculturalAlert)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgriculturalAlertRequest $request, AgriculturalAlert $agriculturalAlert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AgriculturalAlert $agriculturalAlert)
    {
        //
    }

}
