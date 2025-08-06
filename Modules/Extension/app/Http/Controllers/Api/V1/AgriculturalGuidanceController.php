<?php

namespace Modules\Extension\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Responses\ApiResponse;
use App\Services\AttachmentService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use League\Flysystem\UnableToWriteFile;
use Modules\Extension\Http\Requests\AgriculturalGuidance\StoreAgriculturalGuidance;
use Modules\Extension\Http\Requests\AgriculturalGuidance\UpdateAgriculturalGuidance;
use Modules\Extension\Models\AgriculturalGuidance;

class AgriculturalGuidanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            Gate::authorize("viewAny", AgriculturalGuidance::class);

            $agriculturalGuidances = Cache::remember(
                "agriculturalGuidances",
                3600,
                function () {
                    return AgriculturalGuidance::with("addedBy", "attachments")->get();
                });

            return ApiResponse::success([
                'agriculturalGuidances' => $agriculturalGuidances
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
    public function store(StoreAgriculturalGuidance $request)
    {
        try {

            $agriculturalGuidance = AgriculturalGuidance::create(
                $request->validated()
            );

            Cache::forget('agriculturalGuidances');

            if ($request->hasFile('file')) {
                $attachment = AttachmentService::makeAttachment($request->file("file"));
                $agriculturalGuidance->attachments()->save($attachment);
            }

            $agriculturalGuidance->load("addedBy", "attachments");

            return ApiResponse::created([
                'agriculturalGuidance' => $agriculturalGuidance
            ]);
        } catch (UnableToWriteFile $e) {
            return ApiResponse::error("Guidance created, but attachment can't be uploaded");
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    /**
     * Show the specified resource.
     */
    public function show(AgriculturalGuidance $agriculturalGuidance)
    {
        try {
            Gate::authorize('view', AgriculturalGuidance::class);
            
            $agriculturalGuidance = Cache::remember(
                "guidance_{$agriculturalGuidance->id}",
                3600,
                function () use ($agriculturalGuidance) {
                    return $agriculturalGuidance->load('addedBy', 'attachments');
                });

            return ApiResponse::success([
                "agriculturalGuidance" => $agriculturalGuidance
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
    public function update(UpdateAgriculturalGuidance $request, AgriculturalGuidance $agriculturalGuidance)
    {
        try {
            $agriculturalGuidance->update($request->validated());
            $agriculturalGuidance->load("addedBy", 'attachments');

            Cache::forget('agriculturalGuidances');
            Cache::forget("guidance_{$agriculturalGuidance->id}");

            return ApiResponse::success([
                "agriculturalGuidance" => $agriculturalGuidance
            ], "Updated Successfully.");
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AgriculturalGuidance $agriculturalGuidance)
    {
        try {
            Gate::authorize("delete", [AgriculturalGuidance::class, $agriculturalGuidance]);

            AttachmentService::removeAttachments($agriculturalGuidance);

            $agriculturalGuidance->delete();

            Cache::forget("agriculturalGuidances");
            Cache::forget("guidance_{$agriculturalGuidance->id}");

            return ApiResponse::success([
                "agriculturalGuidance"=> $agriculturalGuidance
            ]);
        } catch (AuthorizationException $e) {
            return ApiResponse::unauthorized();
        } catch (Exception $e) {
            return ApiResponse::error();
        }
    }
}
