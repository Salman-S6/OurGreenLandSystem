<?php

namespace Modules\Extension\Models;

use App\Models\Attachment;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Extension\Database\Factories\AgriculturalGuidanceFactory;
use Modules\Extension\Policies\AgriculturalGuidancePolicy;
use Spatie\Translatable\HasTranslations;


class AgriculturalGuidance extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'summary',
        'category',
        'added_by_id',
        'tags',
    ];

    protected array $translatable = [
        'title',
        'category',
        'summary',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tags' => 'array',
    ];


    protected static function newFactory(): AgriculturalGuidanceFactory
    {
        return AgriculturalGuidanceFactory::new();
    }

    /**
     * Get the user who added the guidance.
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by_id');
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

}
