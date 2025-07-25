<?php

namespace Modules\FarmLand\Http\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Modules\FarmLand\Models\WaterAnalysis;

class WaterAnalysisPolicy
{
    /**
     * Summary Of Before.
     *
     * @param \App\Models\User $user
     * @return ?bool
     */
    public function before(User $user): ?bool
    {
        if ($user->hasRole('SuperAdmin')) {
            return true;
        }
        return null;
    }

    /**
     * Determine Whether The User Can View Any Models.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['ProgramManager', 'AgriculturalEngineer', 'DataAnalyst']);
    }

    /**
     * Determine Whether The User Can View The Model.
     *
     * @param \App\Models\User $user
     * @param \Modules\FarmLand\Models\WaterAnalysis $waterAnalysis
     * @return bool
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
     * Determine Whether The User Can Create Models.
     *
     * @param \App\Models\User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['AgriculturalEngineer', 'SoilWaterSpecialist']);
    }

    /**
     * Determine Whether The User Can Update The Model.
     *
     * @param \App\Models\User $user
     * @param \Modules\FarmLand\Models\WaterAnalysis $waterAnalysis
     * @return bool
     */
    public function update(User $user, WaterAnalysis $waterAnalysis): bool
    {
        if ($user->hasAnyRole(['AgriculturalEngineer', 'SoilWaterSpecialist']) && $waterAnalysis->performed_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine Whether The User Can Delete The Model.
     *
     * @param \App\Models\User $user
     * @param \Modules\FarmLand\Models\WaterAnalysis $waterAnalysis
     * @return bool
     *
     */
    public function delete(User $user, WaterAnalysis $waterAnalysis): bool
    {
        return $user->hasRole('AgriculturalEngineer') && $waterAnalysis->performed_by === $user->id;
    }
}
