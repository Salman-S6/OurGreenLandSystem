<?php

namespace Modules\FarmLand\Http\Policies;

use Modules\FarmLand\Models\Rehabilitation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RehabilitationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rehabilitation $rehabilitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rehabilitation $rehabilitation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rehabilitation $rehabilitation): bool
    {
        return false;
    }

}
