<?php

namespace Modules\FarmLand\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\FarmLand\Database\Factories\SoilFactory;
use Spatie\Translatable\HasTranslations;

class Soil extends Model
{
    /** @use HasFactory<\Modules\FarmLand\Database\Factories\SoilFactory> */
    use HasFactory, HasTranslations;

    protected static function newFactory(): SoilFactory
    {
        return SoilFactory::new();
    }


    protected $fillable = [
        "name",
    ];

    public array $translatable = ['name'];
    /**
     * Get the lands that use this soil type.
     */
    public function lands(): HasMany
    {
        return $this->hasMany(Land::class, 'soil_type_id');
    }
}
