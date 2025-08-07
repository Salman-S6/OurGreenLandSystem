<?php

namespace Modules\Resources\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Resources\Database\Factories\SupplierFactory;
use Modules\Resources\Enums\SupplierType;
use Spatie\Translatable\HasTranslations;

class Supplier extends Model
{
    use HasFactory, HasTranslations;

    protected static function newFactory()
    {
        return SupplierFactory::new();
    }
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
