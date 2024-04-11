<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Question extends Model
{
    use HasFactory;

    protected $fillable = ['quiz_id', 'questionable'];


    /**
     * Polymorphic relationship with question model
     */
    public function questionable() : MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relationship with answer model
     */
    public function answers() : HasMany
    {
        return $this->hasMany(Answer::class);
    }
}
