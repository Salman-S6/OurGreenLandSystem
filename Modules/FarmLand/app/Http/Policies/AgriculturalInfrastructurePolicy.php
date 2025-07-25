<?php

namespace Modules\FarmLand\Http\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\FarmLand\Models\AgriculturalInfrastructure;

class AgriculturalInfrastructurePolicy
{
    /**
     * Summary Of Before.
     *
     * @param \App\Models\User $user
     * @return ?bool
     */
    public function before(User $user): ?bool
    {
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }
        return null;
    }

    /**
     * Determine Whether The User Can View Any Models.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasAnyRole(['ProgramManager', 'AgriculturalEngineer']))
            return true;

        return false;
    }

    /**
     * Determine Whether The User Can View The Model.
     *
     * @param \App\Models\User $user
     * @param \Modules\FarmLand\Models\AgriculturalInfrastructure $agriculturalInfrastructure
     * @return bool
     */
    public function view(User $user, AgriculturalInfrastructure $agriculturalInfrastructure): bool
    {
        if ($user->hasAnyRole(['ProgramManager', 'AgriculturalEngineer'])) {
            return true;
        }

        if ($user->hasRole('Farmer')) {
            return $agriculturalInfrastructure->lands()->where('farmer_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Determine Whether The User Can Create Models.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasRole('AgriculturalEngineer');
    }

    /**
     * Determine Whether The User Can Update The Model.
     *
     * @param \App\Models\User $user
     * @param \Modules\FarmLand\Models\AgriculturalInfrastructure $agriculturalInfrastructure
     * @return bool
     */
    public function update(User $user, AgriculturalInfrastructure $agriculturalInfrastructure): bool
    {
        return $user->hasRole('AgriculturalEngineer') &&
            $user->lands()->whereHas('agriculturalInfrastructures', function ($query) use ($agriculturalInfrastructure) {
                $query->where('id', $agriculturalInfrastructure->id);
            })->exists();
    }

    /**
     * Determine Whether The User Can Delete The Model.
     *
     * @param \App\Models\User $user
     * @param \Modules\FarmLand\Models\AgriculturalInfrastructure $agriculturalInfrastructure
     * @return bool
     */
    public function delete(User $user, AgriculturalInfrastructure $agriculturalInfrastructure): bool
    {
        return $this->update($user, $agriculturalInfrastructure);
    }
}
