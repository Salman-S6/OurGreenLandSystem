<?php

namespace Modules\FarmLand\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\CropManagement\Models\Crop;
use Modules\FarmLand\Enums\AnalysisType;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\FarmLand\Enums\SoilAnalysesFertilityLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FarmLand\Database\Factories\IdealAnalysisValueFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class IdealAnalysisValue extends Model
{
    use HasFactory, HasTranslations,LogsActivity;

    /**
     * Summary of newFactory.
     *
     * @return IdealAnalysisValueFactory
     */
    protected static function newFactory(): IdealAnalysisValueFactory
    {
        return IdealAnalysisValueFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "type",
        "crop_id",
        "ph_min",
        "ph_max",
        "salinity_min",
        "salinity_max",
        "fertility_level",
        "water_quality",
        "notes",
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = ['notes'];

        public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('IdealAnalysisValue')
            ->logOnly([
                "type",
                "crop_id",
                "ph_min",
                "ph_max",
                "salinity_min",
                "salinity_max",
                "fertility_level",
                "water_quality"
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ph_min' => 'decimal:2',
        'ph_max' => 'decimal:2',
        'salinity_min' => 'decimal:2',
        'salinity_max' => 'decimal:2',
        'type' => AnalysisType::class,
        'fertility_level' => SoilAnalysesFertilityLevel::class,
    ];

    /**
     * Get the crop that this ideal analysis value belongs to.
     */
    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class, 'crop_id');
    }
}
