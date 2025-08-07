<?php

namespace Modules\CropManagement\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\CropManagement\Models\PestDiseaseCase;

class PestDiseaseCasePolicy
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
        return $user->hasRole(UserRoles::AgriculturalEngineer) ||
            $user->hasRole(UserRoles::ProgramManager) ||
            $user->hasRole(UserRoles::Farmer);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, PestDiseaseCase $pestDiseaseCase): bool

    {
        if ($user->hasRole(UserRoles::ProgramManager)) {
            return true;
        }
         if ($user->hasRole(UserRoles::AgriculturalEngineer)) {
         return $pestDiseaseCase->reported_by === $user->id;
      }
       if ($user->hasRole(UserRoles::Farmer)) {
        return $pestDiseaseCase->cropPlan->land->farmer_id === $user->id;
       }
       return false;
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
    public function update(User $user, PestDiseaseCase $pestDiseaseCase): bool
    {
        return $user->hasRole(UserRoles::AgriculturalEngineer)&&
        $pestDiseaseCase->reported_by===$user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PestDiseaseCase $pestDiseaseCase): bool
    { return $user->hasRole(UserRoles::AgriculturalEngineer)&&
        $pestDiseaseCase->reported_by===$user->id;
    }
}
