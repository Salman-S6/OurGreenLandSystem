<?php

namespace Modules\CropManagement\Policies;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\CropManagement\Models\BestAgriculturalPractice;

class BestAgriculturalPracticePolicy
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
        return $user->hasRole(UserRoles::AgriculturalAlert);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BestAgriculturalPractice $bestAgriculturalPractices): bool
    {
        return  $user->hasRole(UserRoles::AgriculturalAlert) &&
            $user->id == $bestAgriculturalPractices->expert_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(UserRoles::AgriculturalAlert);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BestAgriculturalPractice $bestAgriculturalPractices): bool
    {
        return  $user->hasRole(UserRoles::AgriculturalAlert) && $bestAgriculturalPractices->expert_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BestAgriculturalPractice $bestAgriculturalPractices): bool
    {
        return  $user->hasRole(UserRoles::AgriculturalAlert) && $bestAgriculturalPractices->expert_id === $user->id;
    }

    /**
     * Determine whether the user can force delete the model.
     */
    public function forceDelete(User $user,  BestAgriculturalPractice $bestAgriculturalPractices): bool
    {
        return  $user->hasRole(UserRoles::AgriculturalAlert) &&
            $bestAgriculturalPractices->expert_id === $user->id;
    }
}
