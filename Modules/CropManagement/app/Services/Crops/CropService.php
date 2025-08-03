<?php

namespace Modules\CropManagement\Services\Crops;

use App\Enums\UserRoles;
use App\Helpers\NotifyHelper;
use App\Interfaces\BaseCrudServiceInterface;
use App\Models\User;
use App\Services\BaseCrudService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Modules\CropManagement\Interfaces\Crops\CropInterface;
use Modules\CropManagement\Models\Crop;

class CropService extends BaseCrudService implements CropInterface
{
    use AuthorizesRequests;

    /**
     * Summary of __construct
     * @param \Modules\CropManagement\Models\Crop $crop
     */
    public function __construct(Crop $crop)
    {
        parent::__construct($crop);
    }

    /**
     * Summary of getAll
     * @param array $filters
     * @return array{data: mixed, message: string}
     */
    public function getAll(array $filters = []): iterable
    {
        $this->authorize('viewAny', Crop::class);

        $crops = Cache::remember('allCrops', now()->addMinutes(60), function () use ($filters) {
            return parent::getAll($filters);
        });

        return [

            'data' => $crops
        ];
    }

    /**
     * Summary of getCrop
     * @param mixed $crop
     * @return array{crop: mixed, message: string}
     */
    public function get(Model $crop): Crop
    {
        $this->authorize('view', $crop);

        return $crop;
    }

    /**
     * Summary of store
     * @param array $data
     * @return \Modules\CropManagement\Models\Crop
     */
    public function store(array $data): Crop
    {

        $this->authorize('create', Crop::class);
        return $this->handle(function () use ($data) {
            DB::beginTransaction();
            $crop = new Crop();
            $crop->setTranslations('name', $data['name']);
            $crop->setTranslations('description', $data['description'] ?? []);
            $crop->farmer_id = Auth::id();
            $crop->save();
            Cache::forget('allCrops');
            $userNotify = User::role([UserRoles::SuperAdmin, UserRoles::AgriculturalAlert, UserRoles::Farmer, UserRoles::ProgramManager])->get();
            $cropNameAr = $crop->getTranslation('name', 'ar');
            $cropNameEn = $crop->getTranslation('name', 'en');
            $notificationData = [
                'title' => 'New Crop Created',
                'message' => "A new crop ({$cropNameEn}) / ({$cropNameAr}) has been added by user #" . Auth::id(),
                'type' => 'info',
                'icon' => 'fas fa-seedling',
                'subject' => 'Crop Creation Notification',
            ];

            NotifyHelper::send($userNotify, $notificationData, ['mail']);
            DB::commit();
            return $crop;
        });
    }

    /**
     * Summary of update
     * @param array $data
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Modules\CropManagement\Models\Crop
     */
    public function update(array $data,  $model): Crop
    {
        $this->authorize('update', $model);

        return $this->handle(function () use ($data, $model) {
            if (isset($data['name'])) {
                $model->setTranslations('name', $data['name']);
            }
            if (isset($data['description'])) {
                $model->setTranslations('description', $data['description']);
            }

            $model->save();
            Cache::forget('allCrops');

            return $model;
        });
    }

    /**
     * Summary of destroy
     * @param \Illuminate\Database\Eloquent\Model $model
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return bool
     */
    public function destroy($model): bool
    {
        $this->authorize('delete', $model);

        $hasUnfinishedPlans = $model->cropPlans()
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->exists();

        if ($hasUnfinishedPlans) {
            throw new HttpResponseException(response()->json([
                'message' => 'Cannot delete crop with active plans',
            ], 422));
        }

        return $this->handle(function () use ($model) {
            DB::transaction(function () use ($model) {
                $cropPlans = $model->cropPlans()->with([
                    'cropGrowthStages' => fn($q) => $q->withTrashed(),
                    'cropGrowthStages.bestAgriculturalPractices' => fn($q) => $q->withTrashed(),
                    'productionEstimations' => fn($q) => $q->withTrashed(),
                    'cropGrowthStages.pestDiseaseCases' => fn($q) => $q->withTrashed(),
                    'cropGrowthStages.pestDiseaseCases.recommendations' => fn($q) => $q->withTrashed(),
                ])->get();

                foreach ($cropPlans as $cropPlan) {
                    foreach ($cropPlan->cropGrowthStages as $stage) {
                        $stage->bestAgriculturalPractices()->forceDelete();
                        foreach ($stage->pestDiseaseCases as $case) {
                            $case->recommendations()->forceDelete();
                            $case->forceDelete();
                        }
                        $stage->forceDelete();
                    }

                    $cropPlan->productionEstimations()->forceDelete();
                    $cropPlan->delete();
                }

                $cropNameAr = $model->getTranslation('name', 'ar');
                $cropNameEn = $model->getTranslation('name', 'en');
                $model->delete();

                $userNotify = User::role([
                    UserRoles::SuperAdmin,
                    UserRoles::AgriculturalAlert,
                    UserRoles::Farmer,
                    UserRoles::ProgramManager
                ])->get();

                $notificationData = [
                    'title' => 'Crop Deleted',
                    'message' => "Crop ({$cropNameEn}) / ({$cropNameAr}) and all its related plans have been deleted.",
                    'type' => 'error',
                    'icon' => 'fas fa-trash',
                    'subject' => 'Crop Deletion Notification',
                ];

                NotifyHelper::send($userNotify, $notificationData, ['mail']);
            });

            Cache::forget('allCrops');

            return true;
        });
    }
}
