<?php

namespace Modules\FarmLand\Http\Policies;

use Modules\FarmLand\Models\WaterAnalysis;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class WaterAnalysisPolicy
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
    public function view(User $user, WaterAnalysis $waterAnalysis): bool
    {
        if ($user->hasAnyRole(['ProgramManager', 'DataAnalyst'])) {
            return true;
        }

        if ($user->hasAnyRole(['AgriculturalEngineer', 'SoilWaterSpecialist']) && $waterAnalysis->performed_by === $user->id) {
            return true;
        }

        if ($user->hasRole('Farmer') && $waterAnalysis->land->farmer_id === $user->id) {
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
    public function update(User $user, WaterAnalysis $waterAnalysis): bool
    {
        if ($user->hasAnyRole(['AgriculturalEngineer', 'SoilWaterSpecialist']) && $waterAnalysis->performed_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WaterAnalysis $waterAnalysis): bool
    {
        return $user->hasRole('AgriculturalEngineer') && $waterAnalysis->performed_by === $user->id;
    }
}
