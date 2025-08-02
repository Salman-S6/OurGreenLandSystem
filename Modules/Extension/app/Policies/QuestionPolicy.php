<?php

namespace Modules\Extension\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Extension\Models\Question;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct() {}
    /**
     * Determine whether the user can view any models.
     */
    
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Question $question): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasPermissionTo("api.extension.questions.create"))
            return true;

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Question $question): bool
    {
        if ($user->hasPermissionTo("api.extension.questions.update") &&
            $question->farmer_id === $user->id)
            return true;

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Question $question): bool
    {
        if ($user->hasPermissionTo("api.extension.questions.delete") &&
            $question->farmer_id === $user->id)
            return true;

        return false;
    }
}
