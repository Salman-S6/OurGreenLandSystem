<?php

namespace Modules\CropManagement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Modules\CropManagement\Database\Factories\BestAgriculturalPracticeFactory;

class BestAgriculturalPractice extends Model
{
    use HasFactory, HasTranslations;

    protected static function newFactory(): BestAgriculturalPracticeFactory
    {
        return BestAgriculturalPracticeFactory::new();
    }
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'growth_stage_id',
        'expert_id',
        'practice_type',
        'material',
        'quantity',
        'application_date',
        'notes',
    ];
    public array $translatable = ['notes', 'practice_type','material'];

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

    /**
     * Get the expert adding the best agricultral practice.
     * @return BelongsTo<User, BestAgriculturalPractice>
     */
    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class,'expert_id');
    }

}
