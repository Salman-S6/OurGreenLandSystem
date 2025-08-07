<?php

namespace Modules\CropManagement\Services\Crops;

use App\Enums\UserRoles;
use App\Helpers\NotifyHelper;
use App\Interfaces\BaseCrudServiceInterface;
use App\Models\User;
use App\Services\BaseCrudService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Modules\CropManagement\Models\PestDiseaseCase;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Exception;
use Illuminate\Support\Facades\Cache;
use Modules\CropManagement\Interfaces\Crops\PestDiseaseCasesInterface;
use Modules\CropManagement\Models\CropGrowthStage;
use Modules\CropManagement\Models\CropPlan;

class PestDiseaseCasesService extends BaseCrudService implements PestDiseaseCasesInterface
{
    use AuthorizesRequests;

    /**
     * Summary of __construct
     * @param \Modules\CropManagement\Models\PestDiseaseCase $model
     */
    public function __construct(PestDiseaseCase $model)
    {
        parent::__construct($model);
    }

    /**
     * Override getAll to apply authorization and role-based filtering
     */
    public function getAll(array $filters = []): iterable
    {
        $user = Auth::user();
        $this->authorize('viewAny', PestDiseaseCase::class);

        $filterKey = md5(json_encode($filters));
        $cacheKey = match (true) {
            $user->hasRole(UserRoles::SuperAdmin) => 'pest_cases_all_' . $filterKey,
            $user->hasRole(UserRoles::ProgramManager) => 'pest_cases_manager_' . $filterKey,
            default => 'pest_cases_user_' . $user->id . '_' . $filterKey,
        };

        return $this->handle(function () use ($user, $filters, $cacheKey) {
            return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user, $filters) {
                $query = $this->model->newQuery()
                    ->with([
                        'reporter',
                        'cropGrowthStage',
                        'recommendations'
                    ])
                    ->orderByDesc('id');

                if ($user->hasRole(UserRoles::AgriculturalEngineer)) {
                    $query->where('reported_by', $user->id);
                } elseif ($user->hasRole(UserRoles::Farmer)) {
                    $query->whereHas('cropPlan.land', fn($q) => $q->where('farmer_id', $user->id));
                }

                foreach ($filters as $field => $value) {
                    if ($value !== null && $value !== '') {
                        $query->where($field, $value);
                    }
                }

                return $query->get();
            });
        });
    }


    /**
     * Custom method for showing a specific disease case
     */

    public function get(\Illuminate\Database\Eloquent\Model $model): PestDiseaseCase
    {
        $this->authorize('view', $model);
        return $model->load([
            'reporter',
            'cropGrowthStage',
            'recommendations'
        ]);
    }


    /**
     * Store new pest/disease case with validation and logging
     */
    public function store(array $data): PestDiseaseCase
    {

        return $this->handle(function () use ($data) {
            $this->authorize('create', PestDiseaseCase::class);
            $cropGrowthStage = CropGrowthStage::find($data['crop_growth_id']);
            $plan = $cropGrowthStage->cropPlan;

            if ($plan->status !== 'in-progress') {
                throw new HttpResponseException(response()->json([
                    'message' => 'The crop plan is not in-progress.'
                ], 403));
            }
            $diseaseCase = new PestDiseaseCase();
            $diseaseCase->crop_growth_id = $data['crop_growth_id'];
            $diseaseCase->reported_by = Auth::id();
            $diseaseCase->case_type = $data['case_type'];
            $diseaseCase->setTranslations('case_name', $data['case_name']);
            $diseaseCase->severity = $data['severity'];
            $diseaseCase->setTranslations('description', $data['description']);
            $diseaseCase->discovery_date = $data['discovery_date'];
            $diseaseCase->setTranslations('location_details', $data['location_details']);
            $diseaseCase->save();

            $crop = $plan->crop;
            $cropNameAr = $crop->getTranslation('name', 'ar');
            $cropNameEn = $crop->getTranslation('name', 'en');
            $userNotify = $this->getCropPlanNotificationUsers($plan);
            $notificationData = [
                'title' => 'New Pest/Disease Case Reported',
                'message' => "A new {$diseaseCase->case_type} case has been reported for crop ({$cropNameEn}) / ({$cropNameAr}) in plan #{$plan->id}.",
                'type' => 'warning',
            ];

            NotifyHelper::send($userNotify, $notificationData, ['mail']);

            Cache::forget('pest_cases_all');
            Cache::forget('pest_cases_manager');
            Cache::forget('pest_cases_user_' . Auth::id());
            return $diseaseCase->load([
                'reporter',
                'cropGrowthStage',
                'recommendations'
            ]);
        }, "Failed to store pest/disease case");
    }

    /**
     * Update pest/disease case
     */
    public function update(array $data, \Illuminate\Database\Eloquent\Model $pestDiseaseCase): PestDiseaseCase
    {
        return $this->handle(function () use ($data, $pestDiseaseCase) {
            $this->authorize('update', $pestDiseaseCase);
            $cropGrowthStage = CropGrowthStage::find($pestDiseaseCase->crop_growth_id);
            $plan = $cropGrowthStage->cropPlan;

            if ($plan->status !== 'in-progress') {
                throw new HttpResponseException(response()->json([
                    'message' => 'The crop plan is not in-progress.'
                ], 403));
            }

            if (isset($data['case_type'])) {
                $pestDiseaseCase->case_type = $data['case_type'];
            }

            if (isset($data['case_name'])) {
                $pestDiseaseCase->setTranslations('case_name', $data['case_name']);
            }

            if (isset($data['severity'])) {
                $pestDiseaseCase->severity = $data['severity'];
            }

            if (isset($data['description'])) {
                $pestDiseaseCase->setTranslations('description', $data['description']);
            }

            if (isset($data['discovery_date'])) {
                $pestDiseaseCase->discovery_date = $data['discovery_date'];
            }

            if (isset($data['location_details'])) {
                $pestDiseaseCase->setTranslations('location_details', $data['location_details']);
            }

            $pestDiseaseCase->save();

            $crop = $plan->crop;
            $cropNameAr = $crop->getTranslation('name', 'ar');
            $cropNameEn = $crop->getTranslation('name', 'en');
            $userNotify = $this->getCropPlanNotificationUsers($plan);
            $notificationData = [
                'title' => 'Pest/Disease Case Updated',
                'message' => "A {$pestDiseaseCase->case_type} case was updated for crop ({$cropNameEn}) / ({$cropNameAr}) in plan #{$plan->id}.",
                'type' => 'info',
            ];

            NotifyHelper::send($userNotify, $notificationData, ['mail']);

            Cache::forget('pest_cases_all');
            Cache::forget('pest_cases_manager');
            Cache::forget('pest_cases_user_' . Auth::id());
            return $pestDiseaseCase->load([
                'reporter',
                'cropGrowthStage',
                'recommendations'
            ]);
        }, "Failed to update pest/disease case");
    }


    /**
     * Destroy pest/disease case
     */
    public function destroy(\Illuminate\Database\Eloquent\Model $pestDiseaseCase): bool
    {
        $this->authorize('delete', $pestDiseaseCase);
        Cache::forget('pest_cases_all');
        Cache::forget('pest_cases_manager');
        Cache::forget('pest_cases_user_' . Auth::id());
        return $this->handle(fn() => $pestDiseaseCase->forceDelete());
    }



    /**
     * Summary of getCropPlanNotificationUsers
     * @param \Modules\CropManagement\Models\CropPlan $plan
     * @return \Illuminate\Support\Collection
     */
    private function getCropPlanNotificationUsers(CropPlan $plan): \Illuminate\Support\Collection
    {
        $users = User::role([UserRoles::ProgramManager, UserRoles::SuperAdmin])->get();

        if ($plan->land->farmer && !$users->contains('id', $plan->land->farmer->id)) {
            $users->push($plan->land->farmer);
        }

        if ($plan->land->owner && !$users->contains('id', $plan->land->owner->id)) {
            $users->push($plan->land->owner);
        }

        return $users;
    }
}
