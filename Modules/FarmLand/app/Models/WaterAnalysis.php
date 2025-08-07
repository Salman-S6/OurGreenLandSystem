<?php

namespace Modules\FarmLand\Models;

use App\Models\User;
use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Modules\FarmLand\Enums\WaterAnalysesSuitability;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\FarmLand\Database\Factories\WaterAnalysisFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class WaterAnalysis extends Model
{
    use HasFactory, HasTranslations,LogsActivity;

    /**
     * Summary of newFactory.
     *
     * @return WaterAnalysisFactory
     */
    protected static function newFactory(): WaterAnalysisFactory
    {
        return WaterAnalysisFactory::new();
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
        'water_quality',
        'suitability',
        'contaminants',
        'recommendations',
    ];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('WaterAnalysis')
            ->logOnly([
                    'land_id',
                    'performed_by',
                    'sample_date',
                    'ph_level',
                    'salinity_level',
                    'water_quality',
                    'suitability',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = ['contaminants', 'recommendations'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sample_date' => 'date',
        'ph_level' => 'decimal:2',
        'salinity_level' => 'decimal:2',
        'suitability' => WaterAnalysesSuitability::class,
    ];

    /**
     * Get the land that this water analysis belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function land(): BelongsTo
    {
        return $this->belongsTo(Land::class);
    }

    /**
     * Get the user who performed the water analysis.
     *
     *@return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Summary of attachments.
     *
     * @return MorphMany<Attachment, WaterAnalysis>
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
