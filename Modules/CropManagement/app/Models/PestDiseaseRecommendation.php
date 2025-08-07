<?php

namespace Modules\CropManagement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CropManagement\Database\Factories\PestDiseaseRecommendationFactory;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class PestDiseaseRecommendation extends Model
{
    use HasFactory, HasTranslations, SoftDeletes, LogsActivity;
    protected static function newFactory(): PestDiseaseRecommendationFactory
    {
        return PestDiseaseRecommendationFactory::new();
    }


    /**
     * Summary of getActivitylogOptions
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('pest-disease-recommendation')
            ->logOnly([
                'pest_disease_case_id',
                'recommendation_name',
                'recommended_dose',
                'application_method',
                'safety_notes',
                'recommended_by'
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * The attributes that are mass assignable.
     */

    protected $fillable = [
        'pest_disease_case_id',
        'recommendation_name',
        'recommended_dose',
        'application_method',
        'safety_notes',


    ];

    /**
     * Summary of translatable
     * @var array
     */
    public array $translatable = ['safety_notes', 'recommendation_name', 'application_method'];

    /**
     * Summary of guarded
     * @var array
     */
    protected  $guarded = ['recommended_by'];
    /**
     * Get the pest/disease case for the recommendation.
     */
    public function pestDiseaseCase(): BelongsTo
    {
        return $this->belongsTo(PestDiseaseCase::class);
    }

    /**
     * Get the user who made the recommendation.
     */
    public function recommender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recommended_by');
    }
}
