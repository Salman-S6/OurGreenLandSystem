<?php

 namespace Modules\CropManagement\Interfaces\Crops;

use App\Interfaces\BaseCrudServiceInterface;

 interface CropPlanInterface extends BaseCrudServiceInterface{

    public function show($cropPlan);

    public  function  switchStatusToCancelled($cropPlan);
 }
