<?php

namespace App\Http\Requests\V1;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreQuizRequest extends FormRequest
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
        return [
            'category' => ['required', Rule::in(Category::pluck('id')->toArray())],
            'title' => ['required', 'string', 'unique:quizzes,title'],
            'description' =>  ['required', 'string']
        ];
    }
}
