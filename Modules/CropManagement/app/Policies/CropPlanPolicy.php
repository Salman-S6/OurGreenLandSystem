<?php

namespace Modules\CropManagement\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Modules\CropManagement\Models\CropPlan;

class CropPlanPolicy
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
     * Summary of viewAny
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(UserRoles::AgriculturalEngineer)
            || $user->hasRole(UserRoles::ProgramManager)
            || $user->hasRole(UserRoles::Farmer);
    }

    /**
     * Summary of view
     * @param \App\Models\User $user
     * @param \Modules\CropManagement\Models\CropPlan $cropPlan
     * @return bool
     */
    public function view(User $user, CropPlan $cropPlan): bool
    {

        if ($user->hasRole(UserRoles::AgriculturalEngineer)) {
            return $user->id === $cropPlan->planned_by;
        }


        if ($user->hasRole(UserRoles::ProgramManager)) {
            return true;
        }


        if ($user->hasRole(UserRoles::Farmer)) {
            return $cropPlan->land && $cropPlan->land->farmer_id === $user->id;
        }

        return false;
    }

    /**
     * Summary of create
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasRole(UserRoles::AgriculturalEngineer) ||
            $user->hasRole(UserRoles::ProgramManager);
    }

    /**
     * Summary of update
     * @param \App\Models\User $user
     * @param \Modules\CropManagement\Models\CropPlan $cropPlan
     * @return bool
     */
    public function update(User $user, CropPlan $cropPlan): bool
    {
        if ($user->hasRole(UserRoles::ProgramManager) ) {
            return true;
        }

        if (
            $user->hasRole(UserRoles::AgriculturalEngineer)
            && $user->id === $cropPlan->planned_by
        ) {
            return true;
        }
        return false;
    }

    /**
     * Summary of delete
     * @param \App\Models\User $user
     * @param \Modules\CropManagement\Models\CropPlan $cropPlan
     * @return bool
     */
    public function delete(User $user, CropPlan $cropPlan): bool
    {
        if ($user->hasRole(UserRoles::ProgramManager)) {
            return true;
        }

        if (
            $user->hasRole(UserRoles::AgriculturalEngineer)
            && $user->id === $cropPlan->planned_by
        ) {
            return true;
        }
        return false;
    }
}
