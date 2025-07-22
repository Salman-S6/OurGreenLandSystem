<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\FarmLand\Models\Land;
use Modules\FarmLand\Models\Rehabilitation;
use Modules\FarmLand\Models\SoilAnalysis;
use Modules\FarmLand\Models\WaterAnalysis;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the questions asked by the user (farmer).
     */
    // public function questions(): HasMany
    // {
    //     return $this->hasMany(Question::class, 'farmer_id');
    // }
    
    /**
     * Get the answers provided by experts.
     */
    // public function answers(): HasMany
    // {
    //     return $this->hasMany(Answer::class, 'expert_id');
    // }

    /**
     * Get the supplier profile associated with the user.
     */
    // public function supplier(): HasOne
    // {
    //     return $this->hasOne(Supplier::class);
    // }

    /**
     * Get the lands owned by the user.
     */
    public function ownedLands(): HasMany
    {
        return $this->hasMany(Land::class,'owner_id');
    }

    /**
     * Get the lands where the user is the farmer.
     */
    public function farmerLands(): HasMany
    {
        return $this->hasMany(Land::class,'farmer_id');
    }

    /**
     * Get the input delivery statuses actioned by the user.
     */
    // public function deliveryStatuses(): HasMany
    // {
    //     return $this->hasMany(InputDeliveryStatus::class, 'action_by');
    // }

    /**
     * Get the supplier ratings given by the user.
     */
    // public function supplierRatings(): HasMany
    // {
    //     return $this->hasMany(SupplierRating::class, 'reviewer_id');
    // }

    /**
     * Get the soil analyses performed by the user.
     */
    public function soilAnalyses(): HasMany
    {
        return $this->hasMany(SoilAnalysis::class, 'performed_by');
    }

    /**
     * Get the rehabilitation activities performed by the user.
     */
    public function rehabilitations(): HasMany
    {
        return $this->hasMany(Rehabilitation::class, 'performed_by');
    }

    /**
     * Get the agricultural guidances added by the user.
     */
    // public function agriculturalGuidances(): HasMany
    // {
    //     return $this->hasMany(AgriculturalGuidance::class, 'added_by_id');
    // }

    /**
     * Get the pest and disease cases reported by the user.
     */
    // public function pestDiseaseCases(): HasMany
    // {
    //     return $this->hasMany(PestDiseaseCase::class, 'reported_by');
    // }

    /**
     * Get the pest and disease recommendations made by the user.
     */
    // public function pestDiseasesRecommendations(): HasMany
    // {
    //     return $this->hasMany(PestDiseaseRecommendation::class, 'recommended_by');
    // }

    /**
     * Get the crop plans created by the user.
     */
    // public function cropPlans(): HasMany
    // {
    //     return $this->hasMany(CropPlan::class, 'planned_by');
    // }

    /**
     * Get the crop growth stages recorded by the user.
     */
    // public function cropGrowthStages(): HasMany
    // {
    //     return $this->hasMany(CropGrowthStage::class, 'recorded_by');
    // }

    // public function productionEstimations(): HasMany
    // {
    //     return $this->hasMany(ProductionEstimation::class, 'reported_by');
    // }

    /**
     * Get the agricultural alerts created by the user.
     */
    // public function agriculturalAlerts(): HasMany
    // {
    //     return $this->hasMany(AgriculturalAlert::class, 'created_by');
    // }

    /**
     * Get the water analyses performed by the user.
     */
    public function waterAnalyses(): HasMany
    {
        return $this->hasMany(WaterAnalysis::class, 'performed_by');
    }

}
