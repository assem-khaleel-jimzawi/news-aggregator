<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreArticleRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'nullable|string|max:255',
            'source' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'url' => 'required|url|unique:articles,url',
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