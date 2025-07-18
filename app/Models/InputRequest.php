<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InputRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'requested_by',
        'input_type',
        'description',
        'quantity',
        'status',
        'approved_by',
        'approval_date',
        'delivery_date',
        'notes',
        'selected_supplier_id',
    ];

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
}