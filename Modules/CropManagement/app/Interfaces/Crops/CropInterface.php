<?php

 namespace Modules\CropManagement\Interfaces\Crops;


interface CropInterface
{

    public function getAll();

    public function getCrop($crop);

    public function store($request);

    public function update($request, $crop);

    public function destroy($crop);
}
