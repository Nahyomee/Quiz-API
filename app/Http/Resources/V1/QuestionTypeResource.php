<?php

namespace App\Http\Resources\V1;

use App\Models\BlankQuestion;
use App\Models\ChoiceQuestion;
use App\Models\TrueFalseQuestion;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $type = '';
        $options = '';
        if($this->questionable instanceof TrueFalseQuestion){
            $type = 'true/false';
        }
        elseif($this->questionable instanceof ChoiceQuestion){
            $type = 'multiple choice';
            $options = $this->questionable->options;
            $options = OptionResource::collection($options);
        }
        elseif($this->questionable instanceof BlankQuestion){
            $type = 'fill in the blank';
        }
        return [
            'type' => $type,
            'question' => new QuestionResource($this->questionable),
            'options' => $this->when($this->questionable instanceof ChoiceQuestion,
                     $options) ,
        ];
    }
}
