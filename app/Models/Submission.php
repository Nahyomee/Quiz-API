<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    
    protected $fillable = ['user_id', 'quiz_id', 'score', 'start', 'end'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function quiz() {
        return $this->belongsTo(Quiz::class);
    }

    public function answers() {
        return $this->hasMany(Answer::class);
    }

    public function scopeCompleted(Builder $query) {
        return $query->whereNotNull('end');
    }
}
