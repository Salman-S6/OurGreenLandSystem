<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Soil extends Model
{
    /** @use HasFactory<\Database\Factories\SoilFactory> */
    use HasFactory;

    protected $fillable = [
        "name",
    ];

    /**
     * Get the lands that use this soil type.
     */
    public function lands(): HasMany
    {
        return $this->hasMany(Land::class, 'soil_type_id');
    }
}
