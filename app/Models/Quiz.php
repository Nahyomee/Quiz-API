<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'user_id', 'title', 'description'];

     /**
     * Relationship with question model
     */
    public function questions() : HasMany
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Relationship with user model
    */
    public function user() : BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
