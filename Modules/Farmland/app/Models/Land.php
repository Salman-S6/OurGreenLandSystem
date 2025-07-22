<?php

namespace Modules\FarmLand\Models;

use App\Models\User;
use Modules\FarmLand\Database\Factories\LandFactory;
use Modules\FarmLand\Models\Soil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

// use Modules\FarmLand\Database\Factories\PostFactory;
class Land extends Model
{
    /** @use HasFactory<\Database\Factories\LandFactory> */
    use HasFactory, HasTranslations;


    protected static function newFactory(): LandFactory
    {
        return LandFactory::new();
    }

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
        'gps_coordinates' => 'array',
        'boundary_coordinates' => 'array',
        'rehabilitation_date' => 'date',
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
        return $this->belongsToMany(Rehabilitation::class, 'rehabilitation_land', 'land_id', 'rehabilitation_id');
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
