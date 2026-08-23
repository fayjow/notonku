<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContentRequest extends FormRequest
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
            'type' => ['required', 'string', \Illuminate\Validation\Rule::enum(\App\Enums\ContentType::class)],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:contents,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'original_title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'status' => ['required', 'string', \Illuminate\Validation\Rule::enum(\App\Enums\ContentStatus::class)],
            'duration_minutes' => ['nullable', 'integer', 'min:1'],
            'age_rating' => ['nullable', 'string', 'max:50'],
            'is_featured' => ['boolean'],
            'is_published' => ['boolean'],
            'genres' => ['nullable', 'array'],
            'genres.*' => ['exists:genres,id'],
            'poster' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'backdrop' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ];
    }
}
