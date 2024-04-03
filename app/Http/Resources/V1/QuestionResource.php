<?php

namespace App\Http\Resources\V1;

use App\Models\ChoiceQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'question' => $this->question, 
            'answer' => $this->answer,
            
            
        ];
    }
}
