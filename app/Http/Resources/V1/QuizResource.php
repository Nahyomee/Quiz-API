<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category->name,
            'title' => $this->title,
            'description' => $this->description,
            'slug' => $this->slug,
            'createdBy' => $this->user->name,
            'question_count' => $this->whenCounted('questions'),
            'questions' => QuestionTypeResource::collection($this->questions),
        ];
    }
}
