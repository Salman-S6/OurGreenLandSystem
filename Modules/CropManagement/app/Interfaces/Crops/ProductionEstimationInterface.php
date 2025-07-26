<?php
namespace Modules\CropManagement\Interfaces\Crops;


interface ProductionEstimationInterface {

     public function  index();

    public function store($request);


    public function update($productionEstimation,$request);


    public function getProductionEstimation($productionEstimation);


    public  function  destroy($productionEstimation);
}
