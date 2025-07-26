<?php

namespace  Modules\Resources\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InputDeliveryStatus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'input_request_id',
        'action_by',
        'action_type',
        'rejection_reason',
        'notes',
        'action_date',
    ];

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
}
