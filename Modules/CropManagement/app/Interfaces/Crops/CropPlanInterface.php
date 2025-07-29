<?php

 namespace Modules\CropManagement\Interfaces\Crops;

use App\Interfaces\BaseCrudServiceInterface;

 /**
  * Summary of CropPlanInterface
  */
 interface CropPlanInterface extends BaseCrudServiceInterface{

  
   

   
    public  function  switchStatusToCancelled($cropPlan);
 }
