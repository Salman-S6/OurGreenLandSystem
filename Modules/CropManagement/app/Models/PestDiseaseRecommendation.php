<?php

namespace Modules\CropManagement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CropManagement\Database\Factories\PestDiseaseRecommendationFactory;
use Spatie\Translatable\HasTranslations;
class PestDiseaseRecommendation extends Model
{
    use HasFactory, HasTranslations,SoftDeletes;
    protected static function newFactory(): PestDiseaseRecommendationFactory
    {
        return PestDiseaseRecommendationFactory::new();
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
        'recommended_by',
    ];

    public array $translatable = ['safety_notes','recommendation_name','application_method'];

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
