<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attachment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uploaded_by',
        'path',
        'url',
        'disk',
        'attachable_id',
        'attachable_type',
        'file_name',
        'file_size',
        'mime_type',
    ];

    /**
     * Get the user that uploaded the attachment.
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get the parent attachable model.
     */
    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }
}
