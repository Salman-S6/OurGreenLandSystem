<?php

namespace Modules\Extension\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Extension\Models\Answer;

class AnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasPermissionTo("api.extension.answers.create")) 
            return true;

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Answer $answer): bool
    {
        if ($user->hasPermissionTo("api.extension.answers.update") &&
            $user->id === $answer->expert_id) 
            return true;

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Answer $answer): bool
    {
        if ($user->hasPermissionTo("api.extension.answers.update") &&
            $user->id === $answer->expert_id) 
            return true;

        return false;
    }

}
