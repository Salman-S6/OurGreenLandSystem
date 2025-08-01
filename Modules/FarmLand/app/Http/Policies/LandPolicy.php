<?php

namespace Modules\FarmLand\Http\Policies;

use Modules\FarmLand\Models\Land;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LandPolicy
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
    public function view(User $user, Land $land): bool
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
    public function update(User $user, Land $land): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Land $land): bool
    {
        return false;
    }

}
