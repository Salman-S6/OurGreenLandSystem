<?php

namespace Modules\Resources\app\Policies;

use App\Models\InputDeliveryStatus;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InputDeliveryStatusPolicy
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
    public function view(User $user, InputDeliveryStatus $inputDeliveryStatus): bool
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
    public function update(User $user, InputDeliveryStatus $inputDeliveryStatus): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InputDeliveryStatus $inputDeliveryStatus): bool
    {
        return false;
    }

}
