<?php

namespace Modules\CropManagement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\CropManagement\Database\Factories\PestDiseaseCaseFactory;
use Spatie\Translatable\HasTranslations;

class PestDiseaseCase extends Model
{
    use HasFactory, HasTranslations;

    protected static function newFactory(): PestDiseaseCaseFactory
    {
        return PestDiseaseCaseFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     */
     protected $fillable = [
        'crop_plan_id',
        'reported_by',
        'case_type',
        'case_name',
        'severity',
        'description',
        'discovery_date',
        'location_details',
    ];
    public array $translatable = ['case_type', 'case_name', 'description', 'location_details'];

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
    public function cropPlan(): BelongsTo
    {
        return $this->belongsTo(CropPlan::class);
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

}
