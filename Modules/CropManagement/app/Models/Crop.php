<?php

namespace Modules\CropManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\CropManagement\Database\Factories\CropFactory;
use Spatie\Translatable\HasTranslations;

class Crop extends Model
{
    use HasFactory, HasTranslations;

    protected static function newFactory(): CropFactory
    {
        return CropFactory::new();
    }
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        "name",
        "description",
    ];

    public array $translatable = ['name', 'description'];

    public function cropPlans(): HasMany
    {
        return $this->hasMany(CropPlan::class, "crop_id");
    }

    public function idealAnalysisValues(): HasMany
    {
        return $this->hasMany(IdealAnalysisValue::class, "crop_id");
    }

}
