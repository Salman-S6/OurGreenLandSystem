<?php

namespace Modules\CropManagement\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\CropManagement\Models\PestDiseaseRecommendation;

class PestDiseaseRecommendationPolicy
{

    /**
     * Summary of before
     * @param \App\Models\User $user
     *
     */
    public function before(User $user)
    {
        if ($user->hasRole(UserRoles::SuperAdmin)) {
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRoles::AgriculturalEngineer);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PestDiseaseRecommendation $pestDiseaseRecommendation): bool
    {
        return  $user->hasRole(UserRoles::AgriculturalEngineer) &&
            $user->id == $pestDiseaseRecommendation->recommended_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(UserRoles::AgriculturalEngineer);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, PestDiseaseRecommendation $pestDiseaseRecommendation): bool
    {
        return  $user->hasRole(UserRoles::AgriculturalEngineer) &&
            $pestDiseaseRecommendation->recommended_by === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PestDiseaseRecommendation $pestDiseaseRecommendation): bool
    {
        return  $user->hasRole(UserRoles::AgriculturalEngineer) &&  $pestDiseaseRecommendation->recommended_by === $user->id;
    }

    /**
     * Determine whether the user can force delete the model.
     */
    public function forceDelete(User $user, PestDiseaseRecommendation $pestDiseaseRecommendation): bool
    {
        return  $user->hasRole(UserRoles::AgriculturalEngineer) &&  $pestDiseaseRecommendation->recommended_by === $user->id;
    }
}
