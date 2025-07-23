<?php

namespace Modules\FarmLand\Http\Policies;

use App\Models\User;
use Modules\FarmLand\Models\SoilAnalysis;
use Illuminate\Auth\Access\Response;

class SoilAnalysisPolicy
{
    /**
     * Summary of before
     * @param \App\Models\User $user
     *
     */
    public function before(User $user)
    {
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['ProgramManager', 'AgriculturalEngineer', 'DataAnalyst']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SoilAnalysis $soilAnalysis): bool
    {
        if ($user->hasAnyRole(['ProgramManager', 'DataAnalyst'])) {
            return true;
        }

        if ($user->hasAnyRole(['AgriculturalEngineer', 'SoilWaterSpecialist']) && $soilAnalysis->performed_by === $user->id) {
            return true;
        }

        if ($user->hasRole('Farmer') && $soilAnalysis->land->farmer_id === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['AgriculturalEngineer', 'SoilWaterSpecialist']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SoilAnalysis $soilAnalysis): bool
    {
        if ($user->hasAnyRole(['AgriculturalEngineer', 'SoilWaterSpecialist']) && $soilAnalysis->performed_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SoilAnalysis $soilAnalysis): bool
    {
        return $user->hasRole('AgriculturalEngineer') && $soilAnalysis->performed_by === $user->id;
    }
}
