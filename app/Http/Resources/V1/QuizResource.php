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
            'time' => $this->time,
            'createdBy' => $this->user->name,
            'isPublished' => $this->is_published === 1 ? 'yes' : 'no',
            'questionCount' => $this->whenCounted('questions'),
            'questions' =>  QuestionTypeResource::collection($this->whenLoaded('questions')),
        ];
    }
}
