<?php

namespace Modules\CropManagement\Models;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\CropManagement\Database\Factories\CropPlanFactory;
use Modules\Extension\Models\AgriculturalAlert;
use Modules\FarmLand\Models\Land;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Spatie\Translatable\HasTranslations;

class CropPlan extends Model
{
    use HasFactory, HasTranslations, LogsActivity;

    /**
     * Summary of newFactory
     * @return CropPlanFactory
     */
    protected static function newFactory(): CropPlanFactory
    {
        return CropPlanFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'crop_id',
        'land_id',
        'planned_planting_date',
        'actual_planting_date',
        'planned_harvest_date',
        'actual_harvest_date',
        'seed_quantity',
        'seed_expiry_date',
        'area_size',
        'status',
    ];

    /**
     * Summary of guarded
     * @var array
     */
    protected  $guarded = ['planned_by'];

    /**
     * Summary of getActivitylogOptions
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('crop-plan')
            ->logOnly([
                'crop_id',
                'land_id',
                'planned_by',
                'status',
                'planned_planting_date',
                'actual_planting_date',
                'planned_harvest_date',
                'actual_harvest_date',
                'seed_quantity',
                'area_size',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    /**
     * Summary of translatable
     * @var array
     */
    public  $translatable = ['seed_type'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'planned_planting_date' => 'date',
        'actual_planting_date' => 'date',
        'planned_harvest_date' => 'date',
        'actual_harvest_date' => 'date',
        'seed_expiry_date' => 'date',
        'seed_quantity' => 'decimal:2',
        'area_size' => 'decimal:2',
        'seed_type' => 'array'



    ];

    /**
     * Get the crop for the plan.
     */
    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class);
    }

    /**
     * Get the user who planned the crop.
     */
    public function planner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'planned_by');
    }

    /**
     * Get the land for the plan.
     */
    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    /**
     * Get the production estimations for the crop plan.
     */
    public function productionEstimations(): HasMany
    {
        return $this->hasMany(ProductionEstimation::class);
    }



    /**
     * Get the agricultural alerts for the crop plan.
     */
    public function agriculturalAlerts(): HasMany
    {
        return $this->hasMany(AgriculturalAlert::class);
    }

    /**
     * Summary of cropGrowthStages
     * @return HasMany<CropGrowthStage, CropPlan>
     */
    public function cropGrowthStages(): HasMany
    {
        return $this->hasMany(CropGrowthStage::class, 'crop_plan_id');
    }

    /**
     * Summary of getCreatedAtAttribute
     * @param mixed $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    /**
     * Summary of getUpdatedAtAttribute
     * @param mixed $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }



    /**
     * Summary of getPlannedPlantingDateAttribute
     * @param mixed $value
     * @return string
     */
    public function getPlannedPlantingDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d ');
    }

    /**
     * Summary of getActualPlantingDateAttribute
     * @param mixed $value
     * @return string
     */
    public function getActualPlantingDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }

    /**
     * Summary of getPlannedHarvestDateAttribute
     * @param mixed $value
     * @return string
     */
    public function getPlannedHarvestDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d ');
    }
    /**
     * Summary of getSeedExpiryDateAttribute
     * @param mixed $value
     * @return string
     */
    public function getSeedExpiryDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d ');
    }


    /**
     * Summary of getActualHarvestDateAttribute
     * @param mixed $value
     * @return string
     */
    public function getActualHarvestDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('Y-m-d') : null;
    }
}
