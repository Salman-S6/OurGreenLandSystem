<?php

namespace App\Services;

use App\Models\Attachment;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\UnableToWriteFile;

class AttachmentService 
{
    /**
     * upload the attachment and make a record for it.
     * 
     * @param \Illuminate\Http\UploadedFile|\Illuminate\Http\File $attachment
     * @return Attachment
     */
    public static function makeAttachment(UploadedFile|File $attachment): Attachment
    {
        try {
            $disk = config('filesystems.default', 'local');
            $fileName = uniqid().'-'.$attachment->hashName();
            $filePath = "attachments/{$fileName}";
    
            $data = [
                "uploaded_by" => auth()->user()->id,
                "path" => $filePath,
                "disk" => $disk,
                "file_name" => $fileName,
                "file_size" => $attachment->getSize(),
                "mime_type" => $attachment->getMimeType(),
            ];
    
            return DB::transaction(function () use ($attachment, $data, $disk) {
                Storage::disk($disk)->putFileAs("attachments/", $attachment, $data["file_name"]);
                $fileUrl = Storage::disk($disk)->url($data['path']);
                $data["url"] = $fileUrl;
                $attachment = Attachment::create($data);
                return $attachment;
            });
        
        } catch (UnableToWriteFile $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * delete the attatchment and it's record.
     * @param \App\Models\Attachment $attachment
     * @return void
     */
    public static function removeAttachment(Attachment $attachment): void
    {
        try {
            Storage::delete($attachment->path);
            $attachment->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * remove all attachments related to attachable object
     * 
     * @param \Illuminate\Database\Eloquent\Model $attachable
     * @throws \Exception
     * @return void
     */
    public static function removeAttachments(Model $attachable): void
    {
        try {
            DB::transaction(function () use ($attachable) {
                $attachments = $attachable->attachments;

                foreach ($attachments as $attachment) {
                    if (Storage::exists($attachment->path)) {
                        Storage::delete($attachment->path);
                    }

                    $attachment->delete();
                }
            });
        } catch (Exception $e) {
            throw $e;
        }
    }
}