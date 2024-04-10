<?php

namespace App\Http\Requests\V1;

use App\Models\Category;
use App\Models\Quiz;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuizRequest extends FormRequest
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
        return [
            'category' => ['sometimes', Rule::in(Category::pluck('id')->toArray())],
            'title' => ['sometimes', 'string', Rule::unique('quizzes')->ignore($this->quiz)],
            'description' => ['sometimes', 'string']
        ];
    }
}
