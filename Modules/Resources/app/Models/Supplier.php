<?php

namespace Modules\Resources\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Resources\Enums\SupplierType;
use Spatie\Translatable\HasTranslations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Supplier extends Model
{
   use HasFactory, HasTranslations, LogsActivity; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'supplier_type',
        'phone_number',
        'license_number',
    ];

    public array $translatable = ['supplier_type'];
protected $casts = [
    'supplier_type' => SupplierType::class,
];

        /**
     * Summary of getActivitylogOptions
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('supplier')
            ->logOnly([ 'user_id','supplier_type', 'phone_number',  'license_number',])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
    /**
     * Get the user that owns the supplier profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ratings for the supplier.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(SupplierRating::class);
    }

    public function inputRequests(): HasMany
    {
        return $this->hasMany(InputRequest::class, "selected_supplier_id");
    }
}
