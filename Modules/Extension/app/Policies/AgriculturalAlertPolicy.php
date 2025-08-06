<?php

namespace Modules\Extension\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Extension\Models\AgriculturalAlert;

class AgriculturalAlertPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {   
        // all users could view alerts
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AgriculturalAlert $agriculturalAlert): bool
    {
        // all users could view alerts
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole([
            UserRoles::ProgramManager,
            UserRoles::AgriculturalEngineer,
            UserRoles::DataAnalyst,
            UserRoles::SoilWaterSpecialist]))
            return true;

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AgriculturalAlert $agriculturalAlert): bool
    {
        if ($user->hasRole(UserRoles::ProgramManager))
            return true;

        if ($user->hasRole([
            UserRoles::AgriculturalEngineer,
            UserRoles::DataAnalyst,
            UserRoles::SoilWaterSpecialist]) &&
            $user->id === $agriculturalAlert->created_by)
            return true;

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AgriculturalAlert $agriculturalAlert): bool
    {
        if ($user->hasRole(UserRoles::ProgramManager))
            return true;
        
        if ($user->hasRole([
            UserRoles::AgriculturalEngineer,
            UserRoles::DataAnalyst,
            UserRoles::SoilWaterSpecialist]) &&
            $user->id === $agriculturalAlert->created_by)
            return true;

        return false;
    }
}
