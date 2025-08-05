<?php

namespace Modules\CropManagement\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CropManagement\Database\Factories\PestDiseaseCaseFactory;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PestDiseaseCase extends Model
{
    use HasFactory, HasTranslations, SoftDeletes, LogsActivity;

    /**
     * Summary of newFactory
     * @return PestDiseaseCaseFactory
     */
    protected static function newFactory(): PestDiseaseCaseFactory
    {
        return PestDiseaseCaseFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'crop_growth_id',
        'case_type',
        'case_name',
        'severity',
        'description',
        'discovery_date',
        'location_details',
    ];

    /**
     * Summary of getActivitylogOptions
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('pest-disease-case')
            ->logOnly([
                'crop_growth_id',
                'case_type',
                'case_name',
                'severity',
                'description',
                'discovery_date',
                'location_details',
                'reported_by',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    /**
     * Summary of translatable
     * @var array
     */
    public array $translatable = ['case_name', 'description', 'location_details'];

    /**
     * Summary of guarded
     * @var array
     */
    protected $guarded = ['reported_by'];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'discovery_date' => 'date',
    ];

    /**
     * Get the crop plan associated with the case.
     */
    public function cropGrowthStage(): BelongsTo
    {
        return $this->belongsTo(CropGrowthStage::class, 'crop_growth_id');
    }

    /**
     * Get the user who reported the case.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Get the recommendations for the pest/disease case.
     */
    public function recommendations(): HasMany
    {
        return $this->hasMany(PestDiseaseRecommendation::class, 'pest_disease_case_id');
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
     * Summary of getDiscoveryDateAttribute
     * @param mixed $value
     * @return string
     */
    public function getDiscoveryDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d ');
    }
}
