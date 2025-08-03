<?php

namespace Modules\Resources\Services;

use App\Enums\UserRoles;
use App\Helpers\NotifyHelper;
use App\Models\User;
use App\Services\BaseCrudService;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Resources\Interfaces\InputRequestInterface;
use Modules\Resources\Models\InputDeliveryStatus;
use Modules\Resources\Models\InputRequest;
use Modules\Resources\Models\Supplier;

class InputRequestService extends BaseCrudService implements InputRequestInterface
{
    use AuthorizesRequests;

    /**
     * Summary of __construct
     * @param \Modules\Resources\Models\InputRequest $model
     */
    public function __construct(InputRequest $model)
    {
        parent::__construct($model);
    }


    /**
     * Summary of getAll
     * @param array $filters
     * @return iterable
     */
    public function getAll(array $filters = []): iterable
    {
        $user = Auth::user();
        $this->authorize('viewAny', InputRequest::class);

        $filterKey = md5(json_encode($filters));
        $cacheKey = match (true) {
            $user->hasAnyRole([UserRoles::SuperAdmin, UserRoles::ProgramManager]) => 'input_requests_all_' . $filterKey,
            $user->hasRole(UserRoles::Farmer) => 'input_requests_farmer_' . $user->id . '_' . $filterKey,
            $user->hasRole(UserRoles::Supplier) => 'input_requests_supplier_' . $user->id . '_' . $filterKey,
            default => 'input_requests_user_' . $user->id . '_' . $filterKey,
        };

        return $this->handle(function () use ($user, $filters, $cacheKey) {
            return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user, $filters) {
                $query = $this->model->newQuery()->orderBy('id', 'desc');

                if ($user->hasAnyRole([UserRoles::SuperAdmin, UserRoles::ProgramManager])) {
                    // no restrictions
                } elseif ($user->hasRole(UserRoles::Farmer)) {
                    $query->where('requested_by', $user->id);
                } elseif ($user->hasRole(UserRoles::Supplier)) {
                    $query->where('selected_supplier_id', $user->id);
                }

                foreach ($filters as $field => $value) {
                    if (!is_null($value) && $value !== '') {
                        $query->where($field, $value);
                    }
                }

                return $query->get();
            });
        });
    }



    /**
     * Summary of get
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function get(Model $model): InputRequest
    {
        $this->authorize('view', $model);
        return $model->load(['approvedBy', 'selectedSupplier', 'deliveryStatus']);
    }

    /**
     * Summary of store
     * @param array $data
     * @return \Modules\Resources\Models\InputRequest
     */
    public function store(array $data): InputRequest
    {

        return $this->handle(function () use ($data) {
            $this->authorize('create', InputRequest::class);
            DB::beginTransaction();
            $inputRequest = new InputRequest();
            $inputRequest->requested_by = Auth::id();
            $inputRequest->input_type = $data['input_type'];
            $inputRequest->setTranslations('description', $data['description']);
            $inputRequest->quantity = $data['quantity'];
            $inputRequest->selected_supplier_id = $data['selected_supplier_id'];
            $inputRequest->save();
            $status = new InputDeliveryStatus();
            $status->input_request_id = $inputRequest->id;
            $status->action_by = Auth::id();
            $status->action_type = 'pending';
            $status->action_date = now();
            $status->save();
            DB::commit();
            $userId = Auth::id();

            $userNotify = User::role([UserRoles::SuperAdmin])->get();
            $supplier = User::find($data['selected_supplier_id']);
            if ($supplier && !$userNotify->contains('id', $supplier->id)) {
                $userNotify->push($supplier);
            }
            $notificationData = [
                'title' => 'New Input Request Created',
                'message' => "A new input request by #" . Auth::user()->name . " has been created.",
                'type' => 'info',
            ];
            NotifyHelper::send($userNotify, $notificationData, ['mail']);


            Cache::forget('input_requests_all');
            Cache::forget("input_requests_farmer_{$userId}");
            Cache::forget("input_requests_supplier_{$data['selected_supplier_id']}");

            return $inputRequest;
        }, "Fail Add Request");
    }


    /**
     * Summary of update
     * @param array $data
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return \Modules\Resources\Models\InputRequest
     */

    public function update(array $data, Model $model): InputRequest
    {
        return $this->handle(function () use ($data, $model) {
            $this->authorize('update', $model);
            $user = Auth::user();
            if (in_array($model->status, ['delivered', 'received'])) {
                $canReceive = $user->hasRole(UserRoles::Farmer) &&
                    $model->status === 'delivered' &&
                    ($data['status'] ?? null) === 'received';

                if (!$canReceive) {
                    throw new HttpResponseException(response()->json([
                        "message" => "This request can no longer be modified after it has been delivered or received."
                    ], 403));
                }
            }


            return match (true) {
                $user->hasRole(UserRoles::Supplier)    => $this->updateBySupplier($model, $data),
                $user->hasRole(UserRoles::Farmer)      => $this->updateByFarmer($model, $data),
                $user->hasRole(UserRoles::SuperAdmin)  => $this->updateByAdmin($model, $data),
                default => throw new HttpResponseException(response()->json([
                    "message" => "You are not authorized to update this request."
                ], 403))
            };
        }, "Failed to update InputRequest");
    }

    /**
     * Summary of updateBySupplier
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return Model
     */
    protected function updateBySupplier(Model $model, array $data): InputRequest
    {
        try {
            DB::beginTransaction();
            if ($model->status !== 'approved') {
                throw new HttpResponseException(response()->json([
                    "message" => "You can only update a request after it has been approved."
                ], 403));
            }

            if (!in_array($data['status'], ['in-progress', 'delivered'])) {
                throw new HttpResponseException(response()->json([
                    "message" => "Suppliers are only allowed to update status to in-progress or delivered."
                ], 403));
            }

            $model->status = $data['status'];

            if ($data['status'] === 'delivered') {
                $model->delivery_date = $data['delivery_date'];
            }
            $model->save();
            $status = new InputDeliveryStatus();
            $status->input_request_id = $model->id;
            $status->action_by = Auth::id();
            $status->action_type = $data['status'];
            if (!empty($data['status_notes'])) {
                $status->setTranslations('notes', $data['status_notes']);
            }
            $status->action_date = now();

            $status->save();
            DB::commit();

            $userNotify = User::role([UserRoles::SuperAdmin])->get();
            $farmer = $model->requestedBy;
            if ($farmer && !$userNotify->contains('id', $farmer->id)) {
                $userNotify->push($farmer);
            }
            $notificationData = [
                'title' => 'Input Request Status Updated',
                'message' => "The status of input request #{$model->id} has been updated to '{$model->status}'.",
                'type' => 'info',
            ];
            NotifyHelper::send($userNotify, $notificationData, ['mail']);

            Cache::forget('input_requests_all');
            Cache::forget("input_requests_supplier_" . Auth::id());
            Cache::forget("input_requests_farmer_" . $model->requested_by);
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw  new HttpResponseException(
                response()->json([
                    "message" => "Fail Update Status By Supplier."
                ], 422)
            );
        }
    }

    /**
     * Summary of updateByFarmer
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return Model
     */
    protected function updateByFarmer(Model $model, array $data): InputRequest
    {
        if ($model->status === 'pending') {
            $model->input_type = $data['input_type'];
            $model->setTranslations('description', $data['description']);
            $model->quantity = $data['quantity'];
            $model->selected_supplier_id = $data['selected_supplier_id'];
            $model->save();

            $userNotify = User::role([UserRoles::SuperAdmin])->get();
            $supplier = User::find($data['selected_supplier_id']);
            if ($supplier && !$userNotify->contains('id', $supplier->id)) {
                $userNotify->push($supplier);
            }

            $notificationData = [
                'title' => 'Input Request Updated',
                'message' => "Input request #{$model->id} has been updated by {$model->requestedBy->name}.",
                'type' => 'info',
            ];

            NotifyHelper::send($userNotify, $notificationData, ['mail']);

            Cache::forget('input_requests_all');
            Cache::forget("input_requests_supplier_" . $model->selected_supplier_id);
            Cache::forget("input_requests_farmer_" . $model->requested_by);
            return $model;
        }

        if ($model->status === 'delivered' && ($data['status'] ?? null) === 'received') {
            $model->status = 'received';
            $model->save();
            $status = new InputDeliveryStatus();
            $status->input_request_id = $model->id;
            $status->action_by = Auth::id();
            $status->action_type = $data['status'];
            $status->action_date = now();
            $status->save();

            $userNotify = User::role([UserRoles::SuperAdmin])->get();
            $supplier = $model->selectedSupplier;
            if ($supplier && !$userNotify->contains('id', $supplier->id)) {
                $userNotify->push($supplier);
            }

            $notificationData = [
                'title' => 'Input Request Received',
                'message' => "Input request #{$model->id} has been marked as received by {$model->requestedBy->name}.",
                'type' => 'success',
            ];

            NotifyHelper::send($userNotify, $notificationData, ['mail']);

            Cache::forget('input_requests_all');
            Cache::forget("input_requests_supplier_" . $model->selected_supplier_id);
            Cache::forget("input_requests_farmer_" . $model->requested_by);
            return $model;
        }

        throw new HttpResponseException(response()->json([
            "message" => "You can only edit this request while it is pending or update status from delivered to received."
        ], 403));
    }


    /**
     * Summary of updateByAdmin
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     * @return Model
     */
    protected function updateByAdmin(Model $model, array $data): InputRequest
    {
        $user = Auth::user();

        $model->input_type = $data['input_type'];
        $model->setTranslations('description', $data['description']);
        $model->quantity = $data['quantity'];
        $model->selected_supplier_id = $data['selected_supplier_id'];

        if (!empty($data['status'])) {
            $model->status = $data['status'];
            $model->approved_by = $user->id;

            if ($data['status'] === 'approved') {
                $model->approval_date = $data['approval_date'] ?? now();
            }
        }

        if (!empty($data['notes'])) {
            $model->setTranslations('notes', $data['notes']);
        }


        $status = new InputDeliveryStatus();
        $status->input_request_id = $model->id;
        $status->action_by = $user->id;
        $status->action_type = $data['status'];
        if (!empty($data['status_notes'])) {
            $status->setTranslations('notes', $data['status_notes']);
        }
        $status->action_date = now();

        if ($data['status'] === 'rejected') {
            $status->setTranslations('rejection_reason', $data['rejection_reason']);
        }
        $status->save();
        $model->save();

        $userNotify = collect();
        $farmer = $model->requestedBy;
        if ($farmer) {
            $userNotify->push($farmer);
        }
        $supplier = $model->selectedSupplier;
        if ($supplier && !$userNotify->contains('id', $supplier->id)) {
            $userNotify->push($supplier);
        }
        $notificationData = [
            'title' => 'Input Request Updated by Admin',
            'message' => "Input request #{$model->id} status updated to '{$model->status}' by admin {$user->name}.",
            'type' => 'info',
        ];

        NotifyHelper::send($userNotify, $notificationData, ['mail']);


        return $model;
    }
    /**
     * Summary of destroy
     * @param \Illuminate\Database\Eloquent\Model $model
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @return bool|null
     */
    public function destroy(Model $model): bool
    {
        $this->authorize('delete', $model);

        if ($model->status != "pending" && Auth::user()->hasRole(UserRoles::Farmer)) {
            throw new HttpResponseException(response()->json([
                'message' => 'You cannot cancel or delete a request after it has been approved or processed.'
            ], 403));
        }

        $deleted = parent::destroy($model);

        if ($deleted) {
            Cache::forget('input_requests_all');
            Cache::forget("input_requests_supplier_" . $model->selected_supplier_id);
            Cache::forget("input_requests_farmer_" . $model->requested_by);
        }

        return $deleted;
    }

    
}
