<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WaterAnalysis;
use Illuminate\Auth\Access\Response;

class WaterAnalysisPolicy
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
    public function view(User $user, WaterAnalysis $waterAnalysis): bool
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
    public function update(User $user, WaterAnalysis $waterAnalysis): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WaterAnalysis $waterAnalysis): bool
    {
        return false;
    }
}
