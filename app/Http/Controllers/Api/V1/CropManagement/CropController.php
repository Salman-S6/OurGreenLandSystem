<?php

namespace App\Http\Controllers\Api\V1\CropManagement;

use App\Http\Controllers\Controller;

use App\Http\Requests\Crop\StoreCropRequest;
use App\Http\Requests\Crop\UpdateCropRequest;
use App\Interfaces\Crops\CropInterface;
use App\Models\Crop;

class CropController extends Controller
{
    protected $crop;


    /**
     * Summary of __construct
     * @param \App\Interfaces\Crops\CropInterface $crop
     */
    public  function __construct(CropInterface $crop){
       $this->crop=$crop;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=$this->crop->getAll();
        return response()->json($data,200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCropRequest $request)
    {
        $data=$this->crop->store($request);
        return response()->json($data,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Crop $crop)
    {
        return response()->json($this->crop->getCrop($crop),200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCropRequest $request, Crop $crop)
    {
        $data=$this->crop->update($request,$crop);
        return response()->json($data,200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Crop $crop)
    {
        return response()->json($this->crop->destroy($crop));
    }
}
