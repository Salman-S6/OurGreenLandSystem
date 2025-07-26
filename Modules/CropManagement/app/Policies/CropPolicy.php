<?php

namespace Modules\CropManagement\Policies;


use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\CropManagement\Models\Crop;

class CropPolicy
{
  /**
     * Summary of before
     * @param \App\Models\User $user
     *
     */
    public function before(User $user)
    {
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }
    }
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('ProgramManager') ||
            $user->hasRole('AgriculturalEngineer') ||
            $user->hasRole('Farmer') ||
            $user->hasRole('SoilWaterSpecialist') ||
            $user->hasRole('Supplier') ||
            $user->hasRole('FundingAgency') ||
            $user->hasRole('DataAnalyst');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Crop $crop): bool
    {
        return $user->hasRole('ProgramManager') ||
            $user->hasRole('AgriculturalEngineer') ||
            $user->hasRole('Farmer') ||
            $user->hasRole('SoilWaterSpecialist') ||
            $user->hasRole('Supplier') ||
            $user->hasRole('FundingAgency') ||
            $user->hasRole('DataAnalyst');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole('Farmer')) {
            return true;
        }
        return false;
    }


    /**
     * Summary of update
     * @param \App\Models\User $user
     * @param \Modules\CropManagement\Models\Crop $crop
     * @return bool
     */
    public function update(User $user, Crop $crop): bool
    {
        return $user->hasRole('Farmer') && $crop->farmer_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Crop $crop): bool
    {
         return $user->hasRole('Farmer') && $crop->farmer_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Crop $crop): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Crop $crop): bool
    {
        return false;
    }


}
