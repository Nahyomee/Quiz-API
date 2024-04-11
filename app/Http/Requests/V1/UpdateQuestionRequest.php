<?php

namespace App\Http\Requests\V1;

use App\Models\ChoiceQuestion;
use App\Models\Quiz;
use App\Models\TrueFalseQuestion;
use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $quiz = $this->route('quiz');
        $user = $this->user();
        return $quiz && $user->can('update', $quiz);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules =  [
            'question' => ['nullable', 'string'],
            'answer' => ['nullable', 'string', function($attribute, $values, $fail){
                $quest = $this->route('question');
                if($quest->questionable instanceof TrueFalseQuestion){
                        if(!in_array(strtolower($values), ['true', 'false'])){
                            $fail('The answer should be either true or false');
                        }
                }
                elseif($quest->questionable instanceof ChoiceQuestion){
                    $options = $quest->questionable->options()->pluck('option')->toArray();
                    if(!in_array($values, $options)){
                        $fail('The answer should be one of the options');
                    }
                } 
               
            }],
        ];

        return $rules;
    }
}
