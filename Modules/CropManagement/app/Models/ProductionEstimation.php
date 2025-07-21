<?php

namespace Modules\CropManagement\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Modules\CropManagement\Database\Factories\ProductionEstimationFactory;

class ProductionEstimation extends Model
{
    use HasFactory;

    protected static function newFactory(): ProductionEstimationFactory
    {
        return ProductionEstimationFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'crop_plan_id',
        'expected_quantity',
        'estimation_method',
        'actual_quantity',
        'crop_quality',
        'reported_by',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expected_quantity' => 'decimal:2',
        'actual_quantity' => 'decimal:2',
    ];

    /**
     * Get the crop plan for the production estimation.
     */
    public function cropPlan(): BelongsTo
    {
        return $this->belongsTo(CropPlan::class);
    }

    /**
     * Get the user who reported the estimation.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
