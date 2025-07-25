<?php

namespace Modules\FarmLand\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\FarmLand\Enums\AgriculturalInfrastructuresType;
use Modules\FarmLand\Enums\AgriculturalInfrastructuresStatus;
use Modules\FarmLand\Database\Factories\AgriculturalInfrastructureFactory;

class AgriculturalInfrastructure extends Model
{
    use HasFactory, HasTranslations;

    /**
     * Summary of newFactory.
     *
     * @return AgriculturalInfrastructureFactory
     */
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
     * The attributes that are translatable.
     *
     * @var array
     */
    public array $translatable = ['description'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'installation_date' => 'date',
        'type' => AgriculturalInfrastructuresType::class,
        'status' => AgriculturalInfrastructuresStatus::class,
    ];

    /**
     * Get the land that this infrastructure belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lands(): BelongsToMany
    {
        return $this->belongsToMany(Land::class, "infrastructure_land", "infrastructure_id", "land_id");
    }
}
