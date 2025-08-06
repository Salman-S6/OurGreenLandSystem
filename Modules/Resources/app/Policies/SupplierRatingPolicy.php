<?php

namespace Modules\Resources\app\Policies;

 
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\Resources\Models\SupplierRating;

class SupplierRatingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['admin', 'program_manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SupplierRating $supplierRating): bool
    {
         return $user->hasRole(['admin', 'program_manager']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['admin', 'program_manager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SupplierRating $supplierRating): bool
    {
         return $user->hasRole(['admin', 'program_manager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SupplierRating $supplierRating): bool
    {
         return $user->hasRole(['admin', 'program_manager']);
    }

}
