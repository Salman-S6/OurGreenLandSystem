<?php

namespace Modules\CropManagement\Models;

use App\Models\User;
use  Modules\FarmLand\Models\Land;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\CropManagement\Database\Factories\CropPlanFactory;
use Modules\Extension\Models\AgriculturalAlert;
use Spatie\Translatable\HasTranslations;

class CropPlan extends Model
{
    use HasFactory, HasTranslations;

    protected static function newFactory(): CropPlanFactory
    {
        return CropPlanFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'crop_id',
        'planned_by',
        'land_id',
        'planned_planting_date',
        'actual_planting_date',
        'planned_harvest_date',
        'actual_harvest_date',
        'seed_type',
        'seed_quantity',
        'seed_expiry_date',
        'area_size',
        'status',
    ];
    public array $translatable = ['seed_type', 'status'];

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
     * Get the pest and disease cases for the crop plan.
     */
    public function pestDiseaseCases(): HasMany
    {
        return $this->hasMany(PestDiseaseCase::class);
    }

    /**
     * Get the agricultural alerts for the crop plan.
     */
    public function agriculturalAlerts(): HasMany
    {
        return $this->hasMany(AgriculturalAlert::class);
    }

    public function cropGrowthStages(): HasMany
    {
        return $this->hasMany(CropGrowthStage::class, 'crop_plan_id');
    }
}
