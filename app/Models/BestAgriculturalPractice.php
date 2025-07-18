<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BestAgriculturalPractice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'growth_stage_id',
        'practice_type',
        'material',
        'quantity',
        'application_date',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'decimal:2',
        'application_date' => 'date',
    ];

    /**
     * Get the growth stage for the agricultural practice.
     */
    public function growthStage(): BelongsTo
    {
        return $this->belongsTo(CropGrowthStage::class, 'growth_stage_id');
    }
}