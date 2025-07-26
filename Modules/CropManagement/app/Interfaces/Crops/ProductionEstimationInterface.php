<?php
namespace Modules\CropManagement\Interfaces\Crops;

use App\Interfaces\BaseCrudServiceInterface;


interface ProductionEstimationInterface  extends BaseCrudServiceInterface{

    public function getProductionEstimation($productionEstimation);
  
}
