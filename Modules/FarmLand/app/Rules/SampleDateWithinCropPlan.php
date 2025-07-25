<?php

namespace Modules\FarmLand\Rules;

use Closure;
use Modules\CropManagement\Models\CropPlan;
use Illuminate\Contracts\Validation\ValidationRule;

class SampleDateWithinCropPlan implements ValidationRule
{
    /**
     * The ID of the land to check against.
     * @var int|null
     */
    protected ?int $landId;

    /**
     * Create a new rule instance.
     *
     * @param int|null $landId
     * @return void
     */
    public function __construct(?int $landId)
    {
        $this->landId = $landId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->landId) {
            return;
        }
        $cropPlanExists = CropPlan::where('land_id', $this->landId)
            ->whereDate('planned_planting_date', '<=', $value)
            ->whereDate('planned_harvest_date', '>=', $value)
            ->exists();

        if (!$cropPlanExists) {
            $fail('The selected sample date is outside the range of any active crop plan for this land.');
        }
    }
}
