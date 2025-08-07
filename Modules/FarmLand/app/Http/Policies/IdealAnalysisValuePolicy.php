<?php

namespace Modules\FarmLand\Http\Policies;

use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Auth\Access\Response;
use Modules\FarmLand\Models\IdealAnalysisValue;

class IdealAnalysisValuePolicy
{
    /**
     * Summary Of Before.
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
        return $user->hasAnyRole([UserRoles::ProgramManager, UserRoles::AgriculturalEngineer, UserRoles::DataAnalyst]);
    }

    /**
     * Determine Whether The User Can View The Model.
     *
     * @param User $user
     * @param IdealAnalysisValue $idealAnalysisValue
     * @return bool
     */
    public function view(User $user, IdealAnalysisValue $idealAnalysisValue): bool
    {
        return $user->hasAnyRole([UserRoles::ProgramManager, UserRoles::AgriculturalEngineer, UserRoles::DataAnalyst]);
    }

    /**
     * Determine Whether The User Can Create Models.
     *
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([UserRoles::ProgramManager, UserRoles::AgriculturalEngineer]);
    }

    /**
     * Determine Whether The User Can Update The Model.
     *
     * @param User $user
     * @param IdealAnalysisValue $idealAnalysisValue
     * @return bool
     */
    public function update(User $user, IdealAnalysisValue $idealAnalysisValue): bool
    {
        return $user->hasAnyRole([UserRoles::ProgramManager, UserRoles::AgriculturalEngineer]);
    }

    /**
     * Determine Whether The User Can Delete The Model.
     *
     * @param User $user
     * @param IdealAnalysisValue $idealAnalysisValue
     * @return bool
     */
    public function delete(User $user, IdealAnalysisValue $idealAnalysisValue): bool
    {
        return $user->hasAnyRole([UserRoles::ProgramManager, UserRoles::AgriculturalEngineer]);
    }
}
