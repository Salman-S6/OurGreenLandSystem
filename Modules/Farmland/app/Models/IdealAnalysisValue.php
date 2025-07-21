<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IdealAnalysisValue extends Model
{
    /** @use HasFactory<\Database\Factories\IdealAnalysisValueFactory> */
    use HasFactory;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ph_min' => 'decimal:2',
        'ph_max' => 'decimal:2',
        'salinity_min' => 'decimal:2',
        'salinity_max' => 'decimal:2',
    ];

    /**
     * Get the crop that this ideal analysis value belongs to.
     */
    public function crop(): BelongsTo
    {
        return $this->belongsTo(Crop::class, 'crop_id');
    }

}
