<?php

 namespace Modules\CropManagement\Interfaces\Crops;

 interface CropPlanInterface{

    public function  index();

    public function store($request);

    public function show($cropPlan);

    public function update($request,$cropPlan);

    public function destroy($cropPlan);

    public  function  switchStatusToCancelled($cropPlan);
 }
