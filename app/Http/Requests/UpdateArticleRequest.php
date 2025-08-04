<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
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
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'source' => 'sometimes|required|string|max:255',
            'category' => 'nullable|string|max:255',
            'url' => [
                'sometimes',
                'required',
                'url',
                Rule::unique('articles', 'url')->ignore(request()->route('id')),
            ],
            'published_at' => 'nullable|date',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The article title is required.',
            'source.required' => 'The article source is required.',
            'url.required' => 'The article URL is required.',
            'url.url' => 'The article URL must be a valid URL.',
            'url.unique' => 'An article with this URL already exists.',
        ];
    }
} 