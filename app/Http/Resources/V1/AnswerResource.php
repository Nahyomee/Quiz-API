<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'question' => $this->question->questionable->question,
            'answer' => $this->answer,
            'isCorrect' => $this->is_correct == 1 ? "yes" : "no"
        ];
    }
}
