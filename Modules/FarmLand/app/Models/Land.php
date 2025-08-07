<?php

namespace Modules\FarmLand\Models;

use App\Models\User;
use Modules\CropManagement\Models\CropPlan;
use Modules\FarmLand\Database\Factories\LandFactory;
use Modules\FarmLand\Models\Soil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Modules\FarmLand\Models\Rehabilitation;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

// use Modules\FarmLand\Database\Factories\PostFactory;
class Land extends Model
{
    /** @use HasFactory<\Database\Factories\LandFactory> */
    use HasFactory, HasTranslations,LogsActivity;


    protected static function newFactory(): LandFactory
    {
        return LandFactory::new();
    }

    protected $fillable = [
        "owner_id",
        "farmer_id",
        "area",
        "region",
        "soil_type_id",
        "damage_level",
        "gps_coordinates",
        "boundary_coordinates",
        "rehabilitation_date",
        "region"
    ];
    

    protected $casts = [
        'gps_coordinates' => 'json',
        'boundary_coordinates' => 'json',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Land')
            ->logOnly([
                'region',
                'damage_level',
                'farmer_id',
                'owner_id'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

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

    public function cropPlans(): HasMany
    {
        return $this->hasMany(CropPlan::class, 'land_id');
    }

    public function agriculturalInfrastructures(): BelongsToMany
    {
        return $this->belongsToMany(AgriculturalInfrastructure::class, 'infrastructure_land', 'land_id', 'infrastructure_id');
    }

    /**
     * Scope to order lands by enum damage level (custom priority)
     */
    public function scopePrioritized(Builder $query): Builder
    {
        return $query->orderByRaw("
            FIELD(damage_level, 'high', 'medium', 'low')
        ");
    }

    

}
