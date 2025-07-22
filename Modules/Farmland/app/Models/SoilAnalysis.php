<?php

namespace Modules\FarmLand\Models;

use App\Models\User;
use Database\Factories\SoilAnalysisFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class SoilAnalysis extends Model
{
    use HasFactory, HasTranslations;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sample_date' => 'date',
        'ph_level' => 'decimal:2',
        'salinity_level' => 'decimal:2',
    ];

    /**
     * Get the land that this soil analysis belongs to.
     */
    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    /**
     * Get the user who performed the soil analysis.
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}