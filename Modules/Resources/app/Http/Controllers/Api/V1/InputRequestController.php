<?php

namespace  Modules\Resources\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;


use Modules\Resources\Http\Requests\InputRequest\StoreInputRequestRequest;
use Modules\Resources\Http\Requests\InputRequest\UpdateInputRequestRequest;
use Modules\Resources\Interfaces\InputRequestInterface;
use Modules\Resources\Models\InputRequest ;

class InputRequestController extends Controller
{
    protected InputRequestInterface $input;

    public  function  __construct(InputRequestInterface $input){
           $this->input=$input;
    }
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
    public function store(StoreInputRequestRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InputRequest $inputRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInputRequestRequest $request, InputRequest $inputRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InputRequest $inputRequest)
    {
        //
    }
}
