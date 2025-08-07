<?php

namespace Modules\FarmLand\Rules;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\ValidationRule;

class LandsBelongToUser implements ValidationRule
{
    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $landIds = (array) $value;

        $accessibleLandsCount = DB::table('lands')
            ->whereIn('id', $landIds)
            ->where(function ($query) {
                $query->where('owner_id', $this->user->id)
                    ->orWhere('farmer_id', $this->user->id);
            })
            ->count();

        if (count($landIds) !== $accessibleLandsCount) {
            $fail('You can only link infrastructure to lands that you own or farm.');
        }
    }
}
