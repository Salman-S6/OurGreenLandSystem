<?php

namespace Modules\Extension\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Modules\CropManagement\Models\CropPlan;
use Modules\Extension\Http\Requests\AgriculturalAlert\StoreAgriculturalAlertRequest;
use Modules\Extension\Http\Requests\AgriculturalAlert\UpdateAgriculturalAlertRequest;
use Modules\Extension\Models\AgriculturalAlert;

class AgriculturalAlertController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(CropPlan $cropPlan)
    {
        try {
            Gate::authorize("viewAny", [AgriculturalAlert::class, $cropPlan]);

            $alerts = Cache::remember(
                "cropPlan_{$cropPlan->id}_alerts", 
                now()->addDay(),
                function () use ($cropPlan) {
                    return $cropPlan->agriculturalAlerts()->with(["creator"])->get();
                });

            return ApiResponse::success([
                "alerts" => $alerts
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAgriculturalAlertRequest $request, CropPlan $cropPlan)
    {
        try {
            $alert = AgriculturalAlert::create($request->validated());

            // notification here

            Cache::forget("cropPlan_{$cropPlan->id}_alerts");

            return ApiResponse::success([
                "alert" => $alert,
            ]);
        } catch (Exception) {
            return ApiResponse::error();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CropPlan $cropPlan, AgriculturalAlert $agriculturalAlert)
    {
        try {
            Gate::authorize("view", [AgriculturalAlert::class, $agriculturalAlert]);
            
            $agriculturalAlert = Cache::remember(
                "alert_{$agriculturalAlert->id}",
                now()->addHour(),
                function () use ($agriculturalAlert) {
                    return $agriculturalAlert->load("creator");
                }
            );

            return ApiResponse::success([
                "agriculturalAlert" => $agriculturalAlert
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAgriculturalAlertRequest $request, CropPlan $cropPlan, AgriculturalAlert $agriculturalAlert)
    {
        try {
            $agriculturalAlert->update($request->validated());

            // notification here

            Cache::forget("alert_{$agriculturalAlert->id}");
            Cache::forget("cropPlan_{$cropPlan->id}_alerts");

            return ApiResponse::success([
                "agriculturalAlert"=> $agriculturalAlert
            ]);
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CropPlan $cropPlan, AgriculturalAlert $agriculturalAlert)
    {
        try {
            Gate::authorize("delete", [AgriculturalAlert::class, $agriculturalAlert]);
            
            $agriculturalAlert->delete(); 
            
            Cache::forget("alert_{$agriculturalAlert->id}");
            Cache::forget("cropPlan_{$cropPlan->id}_alerts");

            return ApiResponse::success([
                "agriculturalAlert"=> $agriculturalAlert
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

}
