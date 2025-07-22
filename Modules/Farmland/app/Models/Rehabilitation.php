<?php

namespace Modules\FarmLand\Models;

use App\Models\User;
use Modules\FarmLand\Database\Factories\RehabilitationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Rehabilitation extends Model
{
    use HasFactory, HasTranslations;

    protected static function newFactory(): RehabilitationFactory
    {
        return RehabilitationFactory::new();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event',
        'description',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'performed_at' => 'date',
    ];

    /**
     * Get the land that this rehabilitation activity belongs to.
     */
    public function lands(): BelongsToMany
    {
        return $this->belongsToMany(Land::class, 'rehabilitation_land', 'rehabilitation_id', 'land_id');
    }

    /**
     * Get the user who performed the rehabilitation activity.
     */
    public function performers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'rehabilitation_land', 'rehabilitation_id', 'performed_by');
    }
}
