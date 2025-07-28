<?php

namespace Modules\CropManagement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\CropManagement\Database\Factories\CropGrowthStageFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Support\Facades\Validator;

class CropGrowthStage extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected static function newFactory(): CropGrowthStageFactory
    {
        return CropGrowthStageFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'crop_plan_id',
        'start_date',
        'end_date',
        'name',
        'notes',
        'recorded_by',
    ];

    public array $translatable = ['name', 'notes'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $cropPlan = CropPlan::findOrFail($model->crop_plan_id);

            if ($cropPlan->status !== 'in-progress') {
                $validator = Validator::make([], []);  
                throw new \Illuminate\Validation\ValidationException(
                    $validator,
                    \Illuminate\Http\Response::HTTP_BAD_REQUEST,
                    ['crop_plan_id' => 'Cannot add growth stage to a crop plan that is not in-progress.']
                );
            }
        });
    }
    /**
     * Get the crop plan for the growth stage.
     */
    public function cropPlan(): BelongsTo
    {
        return $this->belongsTo(CropPlan::class);
    }

    /**
     * Get the user who recorded the growth stage.
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function bestAgriculturalPractices(): HasMany
    {
        return $this->hasMany(BestAgriculturalPractice::class, "growth_stage_id");
    }
}
