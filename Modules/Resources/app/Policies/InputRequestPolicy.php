<?php

namespace Modules\Resources\Policies;

use App\Enums\UserRoles;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\Resources\Models\InputRequest;

class InputRequestPolicy
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
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(UserRoles::Farmer)) {
            return true;
        } else if ($user->hasRole(UserRoles::ProgramManager)) {
            return true;
        } else if ($user->hasRole(UserRoles::Supplier)) {
            return true;
        }else {
            return false;
        }
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InputRequest $inputRequest): bool
    {
        if ($user->hasRole(UserRoles::Farmer) && $user->id === $inputRequest->requested_by) {
            return true;
        } else if ($user->hasRole(UserRoles::ProgramManager)) {
            return true;
        } else if ($user->hasRole(UserRoles::Supplier) && $user->id === $inputRequest->selected_supplier_id) {
            return true;
        }else {
            return false;
        }
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasRole(UserRoles::Farmer)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InputRequest $inputRequest): bool
    {
        if ($user->hasRole(UserRoles::Farmer) && $inputRequest->requested_by === $user->id) {
            return true;
        } elseif ($user->hasRole(UserRoles::Supplier) && $user->id === $inputRequest->selected_supplier_id) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InputRequest $inputRequest): bool
    {
        return (
            $user->hasRole(UserRoles::Farmer) && $inputRequest->requested_by === $user->id
        );
    }
}
