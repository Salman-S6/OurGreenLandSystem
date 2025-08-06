<?php

namespace Modules\Extension\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Extension\Models\AgriculturalGuidance;

class AgriculturalGuidancePolicy
{
    use HandlesAuthorization;


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
    public function view(User $user, AgriculturalGuidance $agriculturalGuidance): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if ($user->hasPermissionTo("api.agricultural-guidances.store"))
            return true;

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AgriculturalGuidance $agriculturalGuidance): bool
    {
        if ($user->hasPermissionTo("api.agricultural-guidances.update") && 
            $user->id === $agriculturalGuidance->added_by_id)
            return true;
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AgriculturalGuidance $agriculturalGuidance): bool
    {
        if ($user->hasPermissionTo("api.agricultural-guidances.update") && 
            $user->id === $agriculturalGuidance->added_by_id)
            return true;
        
        return false;
    }
}
