<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Land extends Model
{
    /** @use HasFactory<\Database\Factories\LandFactory> */
    use HasFactory;

    protected $fillable = [
        "user_id",
        "farmer_id",
        "area",
        "soil_type_id",
        "damage_level",
        "gps_coordinates",
        "boundary_coordinates",
        "rehabilitation_date",
    ];

    protected $casts = [
        'gps_coordinates' => 'json',
        'boundary_coordinates' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id');
    }

    public function soilType(): BelongsTo
    {
        return $this->belongsTo(Soil::class, 'soil_type_id');
    }

    public function waterAnalyses(): HasMany
    {
        return $this->hasMany(WaterAnalysis::class, 'land_id');
    }

    public function soilAnalyses(): HasMany
    {
        return $this->hasMany(SoilAnalysis::class, 'land_id');
    }

    public function rehabilitations(): BelongsToMany
    {
        return $this->belongsToMany(Rehabilitation::class, 'rehablilitation_land', 'land_id', 'rehabilitation_id');
    }

    // public function cropPlans(): HasMany
    // {
    //     return $this->hasMany(CropPlan::class, 'land_id');
    // }

    public function agriculturalInfrastructures(): BelongsToMany
    {
        return $this->belongsToMany(AgriculturalInfrastructure::class, 'infrastructure_land', 'land_id', 'infrastructure_id');
    }


}
