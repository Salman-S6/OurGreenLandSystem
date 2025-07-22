<?php

namespace Modules\CropManagement\Policies;


use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\CropManagement\Models\PestDiseaseCase;

class PestDiseaseCasePolicy
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
    public function view(User $user, PestDiseaseCase $pestDiseaseCase): bool

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
    public function update(User $user, PestDiseaseCase $pestDiseaseCase): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, PestDiseaseCase $pestDiseaseCase): bool
    {
        return false;
    }

}
