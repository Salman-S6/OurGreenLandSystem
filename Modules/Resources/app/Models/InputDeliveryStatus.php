<?php

namespace  Modules\Resources\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class InputDeliveryStatus extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'input_request_id',
        'action_by',
        'action_type',
        'action_date',
    ];

    protected  $translatable = ['rejection_reason', 'notes'];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'action_date' => 'datetime',
    ];

    /**
     * Get the input request associated with the status.
     */
    public function inputRequest(): BelongsTo
    {
        return $this->belongsTo(InputRequest::class, 'input_request_id');
    }

    /**
     * Get the user who performed the action.
     */
    public function actionBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    /**
     * Summary of getUpdatedAtAttribute
     * @param mixed $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    /**
     * Summary of getActionDateAttribute
     * @param mixed $value
     * @return string
     */
    public function getActionDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}
