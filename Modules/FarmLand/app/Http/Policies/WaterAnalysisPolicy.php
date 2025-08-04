<?php

namespace Modules\FarmLand\Http\Policies;

use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Auth\Access\Response;
use Modules\FarmLand\Models\WaterAnalysis;

class WaterAnalysisPolicy
{
    /**
     * Summary of before.
     *
     * @param User $user
     * @return ?bool
     */
    public function before(User $user): ?bool
    {
        if ($user->hasRole(UserRoles::SuperAdmin)) {
            return true;
        }
        return null;
    }

    /**
     * Determine Whether The User Can View Any Models.
     *
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([UserRoles::ProgramManager, UserRoles::AgriculturalAlert, UserRoles::DataAnalyst]);
    }

    /**
     * Determine Whether The User Can View The Model.
     *
     * @param User $user
     * @param WaterAnalysis $waterAnalysis
     * @return bool
     */
    public function view(User $user, WaterAnalysis $waterAnalysis): bool
    {
        if ($user->hasAnyRole([UserRoles::ProgramManager, UserRoles::DataAnalyst])) {
            return true;
        }

        if ($user->hasAnyRole([UserRoles::AgriculturalAlert, UserRoles::SoilWaterSpecialist]) && $waterAnalysis->performed_by === $user->id) {
            return true;
        }

        if ($user->id === $waterAnalysis->land->farmer_id) {
            return true;
        }

        if ($user->id === $waterAnalysis->land->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine Whether The User Can Create Models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([UserRoles::AgriculturalAlert, UserRoles::SoilWaterSpecialist]);
    }

    /**
     * Determine Whether The User Can Update The Model.
     *
     * @param User $user
     * @param WaterAnalysis $waterAnalysis
     * @return bool
     */
    public function update(User $user, WaterAnalysis $waterAnalysis): bool
    {
        if ($user->hasAnyRole([UserRoles::AgriculturalAlert, UserRoles::SoilWaterSpecialist]) && $waterAnalysis->performed_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine Whether The User Can Delete The Model.
     *
     * @param User $user
     * @param WaterAnalysis $waterAnalysis
     * @return bool
     */
    public function delete(User $user, WaterAnalysis $waterAnalysis): bool
    {
        return $user->hasRole(UserRoles::AgriculturalAlert) && $waterAnalysis->performed_by === $user->id;
    }
}
