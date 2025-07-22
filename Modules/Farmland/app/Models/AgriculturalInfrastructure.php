<?php

namespace Modules\FarmLand\Models;

use Database\Factories\AgriculturalInfrastructureFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class AgriculturalInfrastructure extends Model
{
    use HasFactory, HasTranslations;

    protected static function newFactory(): AgriculturalInfrastructureFactory
    {
     return AgriculturalInfrastructureFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'status',
        'description',
        'installation_date',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'installation_date' => 'date',
    ];

    /**
     * Get the land that this infrastructure belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lands(): BelongsToMany
    {
        return $this->belongsToMany(Land::class, "infrastructure_land", "infrastructure_id", "land_id");
    }
}