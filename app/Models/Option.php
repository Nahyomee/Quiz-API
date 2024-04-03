<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['choice_questions_id', 'option'];

    public function question()
    {
        return $this->belongsTo(ChoiceQuestion::class, 'choice_questions_id');
    }
}
