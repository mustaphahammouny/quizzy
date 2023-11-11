<?php

namespace App\Http\Requests;

use App\Enums\QuestionTypeList;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateQuestionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question' => ['required', 'string', 'max:255'],
            'type' => ['required', Rule::in(QuestionTypeList::cases())],
            'time' => ['required', 'numeric'],
        ];
    }
}
