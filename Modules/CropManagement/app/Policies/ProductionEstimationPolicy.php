<?php

namespace Modules\CropManagement\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\CropManagement\Models\ProductionEstimation;

class ProductionEstimationPolicy
{
    /**
     * Summary of before
     * @param \App\Models\User $user
     *
     */
    public function before(User $user){
        if($user->hasRole(UserRoles::SuperAdmin)){
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRoles::AgriculturalAlert);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProductionEstimation $productionEstimation): bool
    {
        return $user->hasRole(UserRoles::AgriculturalAlert)&&
        $productionEstimation->reported_by===$user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(UserRoles::AgriculturalAlert);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProductionEstimation $productionEstimation): bool
    {
        return $user->hasRole(UserRoles::AgriculturalAlert)&&
        $productionEstimation->reported_by===$user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProductionEstimation $productionEstimation): bool
    {
        return $user->hasRole(UserRoles::AgriculturalAlert)&&
        $productionEstimation->reported_by===$user->id;
    }

}
