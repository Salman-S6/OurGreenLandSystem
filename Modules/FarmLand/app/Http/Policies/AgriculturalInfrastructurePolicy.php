<?php

namespace Modules\FarmLand\Http\Policies;

use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Auth\Access\Response;
use Modules\FarmLand\Models\AgriculturalInfrastructure;

class AgriculturalInfrastructurePolicy
{
    /**
     * Summary of before.
     *
     * @param User $user
     * @return ?bool
     */
    public function before(User $user): ?bool
    {
        if ($user->hasRole(UserRoles::SuperAdmin)) {
            return true;
        }
        return null;
    }

    /**
     * Determine Whether The User Can View Any Models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasAnyRole([UserRoles::ProgramManager, UserRoles::AgriculturalEngineer]))
            return true;

        return false;
    }

    /**
     * Determine Whether The User Can View The Model.
     *
     * @param User $user
     * @param AgriculturalInfrastructure $infrastructure
     * @return bool
     */
    public function view(User $user, AgriculturalInfrastructure $infrastructure): bool
    {
        if ($user->hasAnyRole([UserRoles::ProgramManager, UserRoles::AgriculturalEngineer])) {
            return true;
        }

        return $infrastructure->lands()
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhere('farmer_id', $user->id);
            })->exists();
    }

    /**
     * Determine Whether The User Can Create Models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([UserRoles::AgriculturalEngineer, UserRoles::Farmer]);
    }

    /**
     * Determine Whether The User Can Update The Model.
     *
     * @param User $user
     * @param AgriculturalInfrastructure $infrastructure
     * @return bool
     */
    public function update(User $user, AgriculturalInfrastructure $infrastructure): bool
    {
        if ($user->hasRole(UserRoles::AgriculturalEngineer))
            return true;

        return $infrastructure->lands()
            ->where(function ($query) use ($user) {
                $query->where('owner_id', $user->id)
                    ->orWhere('farmer_id', $user->id);
            })->exists();
    }

    /**
     * Determine Whether The User Can Delete The Model.
     *
     * @param User $user
     * @param AgriculturalInfrastructure $infrastructure
     * @return bool
     */
    public function delete(User $user, AgriculturalInfrastructure $infrastructure): bool
    {
        return $this->update($user, $infrastructure);
    }
}
