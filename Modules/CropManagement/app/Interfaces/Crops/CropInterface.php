<?php

 namespace Modules\CropManagement\Interfaces\Crops;

use App\Interfaces\BaseCrudServiceInterface;
interface CropInterface extends BaseCrudServiceInterface
{
        public function getCrop($crop);
}
