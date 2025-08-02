<?php

namespace App\Policies;

use App\Enums\UserRoles;
use App\Models\Attachment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttachmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasPermissionTo("attachments.store"))
            return true;

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Attachment $attachment): bool
    {
        if ($user->id === $attachment->uploaded_by ||
            $user->hasRole(UserRoles::ProgramManager))
            return true;
            
        return false;
    }
}
