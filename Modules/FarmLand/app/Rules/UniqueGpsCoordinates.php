<?php

namespace Modules\FarmLand\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Modules\FarmLand\Models\Land;

class UniqueGpsCoordinates implements Rule
{
    protected $latitude;
    protected $longitude;

    /**
     *  Check that there are no duplicate coordinates in the land table.
     * @param string $attribute 
     * @param mixed $value 
     * @return bool
     */
     protected $message;

    public function passes($attribute, $value)
    {
 
        if (!is_array($value) || !isset($value['lat']) || !isset($value['lng'])) {
            return true;
        }

  
        $lat = (float) $value['lat'];
        $lng = (float) $value['lng'];

 
        $exists = Land::whereRaw("JSON_EXTRACT(gps_coordinates, '$.lat') = ? AND JSON_EXTRACT(gps_coordinates, '$.lng') = ?", [$lat, $lng])
            ->exists();

        if ($exists) {
            $this->message = 'An exact GPS point with this latitude and longitude already exists.';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message ?? 'The GPS coordinates must be unique.';
    }
}