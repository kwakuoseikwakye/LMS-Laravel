<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class BookRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'author' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'publication_year' => ['required', 'integer', 'min:1000', 'max:' . (date('Y') + 1)],
            'category_id' => ['required', 'exists:categories,id'],
            'cover_image' => ['nullable', 'image', 'max:2048']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            apiErrorResponse("Book validation failed. " . join(". ", $validator->errors()->all()), 422)
        );
    }
}
