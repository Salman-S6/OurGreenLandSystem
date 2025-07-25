<?php

namespace Modules\FarmLand\Http\Policies;

use Modules\FarmLand\Models\Land;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LandPolicy
{

    /**
     * Perform pre-authorization checks.
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
        return $user->hasRole('AgriculturalEngineer') ||
            $user->hasRole('Supplier') ||
            $user->hasRole('FundingAgency');
    }

    /**
     * Determine whether the user can view the land.
     * - Farmer can view his own land.
     * - SoilWaterSpecialist can view lands they analyzed.
     */
    public function view(User $user, Land $land): bool
    {
        return (
            ($user->hasRole('Farmer') && $user->id === $land->farmer_id)
            ||
            ($user->hasRole('SoilWaterSpecialist') && $land->isAnalyzedBy($user))
        );
    }


    /**
      * Determine whether the user can create a land.
     * - Allowed for: Farmer only.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Farmer') ;
    }

    /**
     * Determine whether the user can update the land.
     * - Allowed only for the Farmer who owns the land.
     */
    public function update(User $user, Land $land): bool
    {
        return $user->hasRole('Farmer') && $user->is($land->farmer);
    }

     /**
     * Determine whether the user can delete the land.
     * - Allowed only for the Farmer who owns the land.
     */
    public function delete(User $user, Land $land): bool
    {
        return $user->hasRole('Farmer') && $user->is($land->farmer);;
    }
  

}
