<?php

namespace Modules\Extension\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Extension\Database\Factories\AnswerFactory;
use Spatie\Translatable\HasTranslations;

class Answer extends Model
{
    use HasFactory, HasTranslations;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'expert_id',
        'question_id',
        'answer_text',
    ];

    protected array $translatable = [
        'answer_text'
    ];

    protected static function newFactory(): AnswerFactory
    {
        return AnswerFactory::new();
    }

    /**
     * Get the expert who provided the answer.
     */
    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    /**
     * Get the question that this answer belongs to.
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
