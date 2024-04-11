<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubmissionResource extends JsonResource
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
            'userId' => $this->user_id, 
            'user' => $this->user->name, 
            'quizId' => $this->quiz_id, 
            'score' => $this->score, 
            'start' => $this->start,
            'end'=> $this->end,
            'answerCount' => $this->whenCounted('answers'),
            'answers' =>  AnswerResource::collection($this->whenLoaded('answers')),
        ];
    }
}
