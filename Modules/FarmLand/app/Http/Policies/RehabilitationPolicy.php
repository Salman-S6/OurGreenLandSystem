<?php

namespace Modules\FarmLand\Http\Policies;

use Modules\FarmLand\Models\Rehabilitation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RehabilitationPolicy
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
           return $user->hasRole([
            'Supplier',
            'ProgramManager',
            'DataAnalyst',
        ]);
    }

/**
 * Determine whether the user can view the rehabilitation record.
 *
 * - ProgramManager can view all records.
 * - Farmer can view records only if one of their lands is involved in the rehabilitation.
 * - SoilWaterSpecialist can view records they participated in (as a performer).
 */
    public function view(User $user, Rehabilitation $rehabilitation): bool
    {
        if ($user->hasRole('ProgramManager')) {
            return true;
        }

        if ($user->hasRole('Farmer')) {
            return $user->farmerLands()
            ->whereHas('rehabilitations', fn($q) => $q->where('rehabilitation_id', $rehabilitation->id))
            ->exists();

        }

        if ($user->hasRole('SoilWaterSpecialist')) {
            return $rehabilitation->performers()->where('users.id', $user->id)->exists();
        }

         return false;
    }

    /**
     * Only ProgramManager can create rehabilitation records.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('ProgramManager');
    }

    /**
     * Only ProgramManager can update rehabilitation records.
     */
    public function update(User $user, Rehabilitation $rehabilitation): bool
    {
        return $user->hasRole('ProgramManager');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rehabilitation $rehabilitation): bool
    {
        return  $user->hasRole('ProgramManager');
    }

}
