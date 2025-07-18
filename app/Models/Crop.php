<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Crop extends Model
{
    /** @use HasFactory<\Database\Factories\CropFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "description",
    ];

    public function cropPlans(): HasMany
    {
        return $this->hasMany(CropPlan::class, "crop_id");
    }

    public function idealAnalysisValues(): HasMany
    {
        return $this->hasMany(IdealAnalysisValue::class, "crop_id");
    }
}
