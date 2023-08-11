<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Get the value of the model's route key.
     */
    public function getRouteKeyName(): mixed
    {
        return 'slug';
    }

    /**
     * Relationship with quiz model
     */
    public function quizzes() : HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    /**
     * Get the category in capitalized case.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
        );
    }
}
