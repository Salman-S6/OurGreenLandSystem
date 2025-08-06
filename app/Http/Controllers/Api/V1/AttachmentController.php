<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\AttachableModels;
use App\Http\Controllers\Controller;
use App\Http\Requests\Attachment\StoreAttachmentRequest;
use App\Http\Responses\ApiResponse;
use App\Models\Attachment;
use App\Services\AttachmentService;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use League\Flysystem\UnableToWriteFile;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Model $attachalbe)
    {
        try {
            Gate::authorize("viewAny", [Attachment::class, $attachalbe]);

            $attachments = Cache::remember(
                class_basename($attachalbe) . "_{$attachalbe->id}_attachments",
                3600,
                function () use ($attachalbe) {
                    return $attachalbe->attachments;
                }
            );
    
            return response()->json([
                "attachments" => $attachments
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::error("this action is unauthorized");
        } catch (Exception $e) {
            return ApiResponse::error("unable to get attachments.", 500, [
                "errors" => [
                    "error" => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttachmentRequest $request, Model $attachalbe)
    {
        try {
            $attachment = AttachmentService::makeAttachment($request->file("file"));

            $attachalbe->attachments()->save($attachment);

            Cache::forget(class_basename($attachalbe) . "_{$attachalbe->id}_attachments");

            return ApiResponse::success([
                "attachment" => $attachment
            ]);
        } catch (UnableToWriteFile $e) {
            return ApiResponse::error("attachment can't be uploaded");
        } catch (Exception $e) {
            return ApiResponse::error("unable to add attachment.");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attachment $attachment)
    {
        try {
            Gate::authorize("delete", [Attachment::class, $attachment]);
    
            $attachalbe = $attachment->attachable;
            AttachmentService::removeAttachment($attachment);
    
            Cache::forget(class_basename($attachalbe) . "_{$attachalbe->id}_attachments");
        
            return ApiResponse::success([
                "attachment" => $attachment
            ]);

        } catch (AuthorizationException $e) {
            return ApiResponse::error("this action is unauthorized");
        } catch (Exception $e) {
            return ApiResponse::error("unable to delete attachment.");
        }
    }
}
