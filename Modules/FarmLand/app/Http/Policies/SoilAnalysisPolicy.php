<?php

namespace Modules\FarmLand\Http\Policies;

use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Auth\Access\Response;
use Modules\FarmLand\Models\SoilAnalysis;

class SoilAnalysisPolicy
{
    /**
     * Summary of before.
     *
     * @param $user
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
     * @param $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([UserRoles::ProgramManager, UserRoles::AgriculturalEngineer, UserRoles::DataAnalyst]);
    }

    /**
     * Determine Whether The User Can View The Model.
     *
     * @param $user
     * @param SoilAnalysis $soilAnalysis
     * @return bool
     */
    public function view(User $user, SoilAnalysis $soilAnalysis): bool
    {
        if ($user->hasAnyRole([UserRoles::ProgramManager, UserRoles::DataAnalyst])) {
            return true;
        }

        if ($user->hasAnyRole([UserRoles::AgriculturalEngineer, UserRoles::SoilWaterSpecialist]) && $soilAnalysis->performed_by === $user->id) {
            return true;
        }

        if ($user->id === $soilAnalysis->land->farmer_id) {
            return true;
        }

        if ($user->id === $soilAnalysis->land->owner_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine Whether The User Can Create Models.
     *
     * @param $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([UserRoles::AgriculturalEngineer, UserRoles::SoilWaterSpecialist]);
    }

    /**
     * Determine Whether The User Can Update The Model.
     *
     * @param $user
     * @param SoilAnalysis $soilAnalysis
     * @return bool
     */
    public function update(User $user, SoilAnalysis $soilAnalysis): bool
    {
        if ($user->hasAnyRole([UserRoles::AgriculturalEngineer, UserRoles::SoilWaterSpecialist]) && $soilAnalysis->performed_by === $user->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine Whether The User Can Delete The Model.
     *
     * @param $user
     * @param SoilAnalysis $soilAnalysis
     * @return bool
     */
    public function delete(User $user, SoilAnalysis $soilAnalysis): bool
    {
        return $user->hasRole(UserRoles::AgriculturalEngineer) && $soilAnalysis->performed_by === $user->id;
    }
}
