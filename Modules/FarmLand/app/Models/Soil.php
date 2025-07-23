<?php

namespace Modules\FarmLand\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Soil extends Model
{
    /** @use HasFactory<\Database\Factories\SoilFactory> */
    use HasFactory, HasTranslations;

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
