<?php

namespace Modules\Resources\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;


class InputRequest extends Model
{
    use HasFactory, HasTranslations, LogsActivity;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'input_type',
        'description',
        'quantity',
        'status',
        'approval_date',
        'delivery_date',
        'notes',
        'selected_supplier_id',
    ];
    /**
     * Summary of translatable
     * @var array
     */
    public $translatable = ['notes', 'description'];

    /**
     * Summary of guarded
     * @var array
     */
    protected $guarded = ['requested_by', 'approved_by'];

    /**
     * Summary of getActivitylogOptions
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('input-request')
            ->logOnly([
                'input_type',
                'description',
                'quantity',
                'status',
                'approval_date',
                'delivery_date',
                'notes',
                'selected_supplier_id',
                'requested_by',
                'approved_by',
            ])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'approval_date' => 'datetime',
        'delivery_date' => 'datetime',
    ];

    /**
     * Get the user who requested the input.
     */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    /**
     * Get the user who approved the request.
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the selected supplier for the request.
     */
    public function selectedSupplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'selected_supplier_id');
    }

    public function deliveryStatus(): HasMany
    {
        return $this->hasMany(InputDeliveryStatus::class, "input_request_id");
    }

    /**
     * Summary of getCreatedAtAttribute
     * @param mixed $value
     * @return string
     */
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
     * Summary of getDeliveryDateAttribute
     * @param mixed $value
     * @return string
     */
    public  function getDeliveryDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }
}
