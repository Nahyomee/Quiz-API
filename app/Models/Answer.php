<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = ['submission_id', 'question_id', 'answer', 'is_correct'];
    /**
     * Relationship with question model
     */
    public function question() : BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    /**
     * Relationship with submissions model
     */
    public function submissions() : BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
