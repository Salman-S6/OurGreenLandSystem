<?php

namespace Modules\Extension\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Extension\Database\Factories\AgriculturalAlertFactory;
use Spatie\Translatable\HasTranslations;
// use Modules\Extension\Database\Factories\AgriculturalAlertFactory;

class AgriculturalAlert extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'message',
        'crop_plan_id',
        'alert_level',
        'alert_type',
        'send_time',
        'created_by',
    ];

    protected array $translatable = [
        'title',
        'message',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'send_time' => 'datetime',
    ];

    protected static function newFactory(): AgriculturalAlertFactory
    {
        return AgriculturalAlertFactory::new();
    }

    /**
     * Get the crop plan associated with the alert.
     */
    // public function cropPlan(): BelongsTo
    // {
    //     return $this->belongsTo(CropPlan::class);
    // }

    /**
     * Get the user who created the alert.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
