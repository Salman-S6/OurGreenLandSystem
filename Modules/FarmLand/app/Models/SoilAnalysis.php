<?php

namespace Modules\FarmLand\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\FarmLand\Enums\SoilAnalysesFertilityLevel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FarmLand\Database\Factories\SoilAnalysisFactory;

class SoilAnalysis extends Model
{
    use HasFactory, HasTranslations;

    /**
     * Summary of newFactory.
     * 
     * @return SoilAnalysisFactory
     */
    protected static function newFactory(): SoilAnalysisFactory
    {
        return SoilAnalysisFactory::new();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'land_id',
        'performed_by',
        'sample_date',
        'ph_level',
        'salinity_level',
        'fertility_level',
        'nutrient_content',
        'contaminants',
        'recommendations',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = ['nutrient_content', 'contaminants', 'recommendations'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sample_date' => 'date',
        'ph_level' => 'decimal:2',
        'salinity_level' => 'decimal:2',
        'fertility_level' => SoilAnalysesFertilityLevel::class,
    ];

    /**
     * Get the land that this soil analysis belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    /**
     * Get the user who performed the soil analysis.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
