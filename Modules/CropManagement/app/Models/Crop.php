<?php

namespace Modules\CropManagement\Models;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Modules\CropManagement\Database\Factories\CropFactory;
use Modules\Farmland\Models\IdealAnalysisValue;
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
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "description",
    ];

     public $translatable = ['name', 'description'];

    protected $guarded = ['farmer_id'];

    public function cropPlans(): HasMany
    {
        return $this->hasMany(CropPlan::class, "crop_id");
    }

    public function idealAnalysisValues(): HasMany
    {
        return $this->hasMany(IdealAnalysisValue::class, "crop_id");
    }

    /**
     * Summary of farmer
     * @return BelongsTo<User, Crop>
     */
    public function farmer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'farmer_id', 'id');
    }
    /**
     * Summary of getCreatedAtAttribute
     * @param mixed $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    /**
     * Summary of getUpdatedAtAttribute
     * @param mixed $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

}
