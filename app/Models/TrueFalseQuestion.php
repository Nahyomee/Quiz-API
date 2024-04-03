<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TrueFalseQuestion extends Model
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
}
