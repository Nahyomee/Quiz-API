<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'user_id', 'title', 'description', 'time', 'slug'];

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
        return $this->belongsTo(User::class);
    }

     /**
     * Relationship with category model
    */
    public function category() : BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relationship with submissions model
     */
    public function submissions() : HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function scopePublished(Builder $query) {
        return $query->where('is_published', 1);
    }

      /**
     * Get the quiz in capitalized case.
     */
    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
        );
    }
}
