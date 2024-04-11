<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ChoiceQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'answer'];
    /**
     * Relationship with question moel
     */
    public function questions() : MorphMany
    {
        return $this->morphMany(Question::class, 'questionable');
    }

    public function options() : HasMany
    {
        return $this->hasMany(Option::class, 'choice_questions_id');
    }
}
