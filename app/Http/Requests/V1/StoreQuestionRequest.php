<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user != null ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules =  [
            'type' => ['required', 'in:blank,multiple choice,true false'],
            'question' => ['string', 'required'],
            'answer' => ['string', 'required', function($attribute, $values, $fail){
                if($this->type === 'true false'){
                    if(!in_array(strtolower($values), ['true', 'false'])){
                        $fail('The answer should be either true or false');
                    }
                }
               
            }],
        ];
        if($this->type === 'multiple choice'){
            $rules += [
                'options' => ['required', 'array', function($attribute, $values, $fail){
                    if($this->type === 'multiple choice'){
                        if(!in_array($this->answer, $this->options)){
                            $fail('The answer should be one of the options');
                        }
                    }
        }],
                'options.*' => ['required', 'string']
            ];
        }

        return $rules;
    }
}
