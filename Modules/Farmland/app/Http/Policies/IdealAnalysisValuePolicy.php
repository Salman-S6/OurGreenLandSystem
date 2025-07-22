<?php

namespace Modules\FarmLand\Http\Policies;

use Modules\FarmLand\Models\IdealAnalysisValue;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class IdealAnalysisValuePolicy
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
    public function view(User $user, IdealAnalysisValue $idealAnalysisValue): bool
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
    public function update(User $user, IdealAnalysisValue $idealAnalysisValue): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, IdealAnalysisValue $idealAnalysisValue): bool
    {
        return false;
    }

}
