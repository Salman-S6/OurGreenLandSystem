<?php

namespace Modules\FarmLand\Rules;

use Illuminate\Contracts\Validation\Rule;
use Modules\FarmLand\Models\Land;

class UniqueBoundaryCoordinates implements Rule
{
    protected $message;

    public function passes($attribute, $value)
    {
        if (!is_array($value)) return true;

   
        $normalized = json_encode(collect($value)->sort()->values());

        $exists = Land::get()
            ->filter(function ($land) use ($normalized) {
                return json_encode(collect($land->boundary_coordinates)->sort()->values()) === $normalized;
            })
            ->isNotEmpty();

        if ($exists) {
            $this->message = 'An identical land boundary already exists.';
            return false;
        }

        return true;
    }

    public function message()
    {
        return $this->message ?? 'The boundary coordinates must be unique.';
    }
}
