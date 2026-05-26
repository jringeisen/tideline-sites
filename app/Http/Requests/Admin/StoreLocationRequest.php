<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->is_admin === true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200'],
            'slug' => ['nullable', 'string', 'alpha_dash', 'max:200', Rule::unique('locations', 'slug')],
            'display_name' => ['required', 'string', 'max:200'],
            'region' => ['nullable', 'string', 'max:100'],
            'tagline' => ['nullable', 'string', 'max:100'],
            'hero_subhead' => ['nullable', 'string', 'max:255'],
            'intro' => ['nullable', 'string', 'max:2000'],
            'why_local' => ['nullable', 'string', 'max:2000'],
            'body' => ['nullable', 'string'],
            'segments' => ['nullable', 'array'],
            'segments.*.title' => ['nullable', 'string', 'max:255'],
            'segments.*.body' => ['nullable', 'string', 'max:1000'],
            'faqs' => ['nullable', 'array'],
            'faqs.*.question' => ['nullable', 'string', 'max:255'],
            'faqs.*.answer' => ['nullable', 'string', 'max:1000'],
            'lat' => ['nullable', 'numeric', 'between:-90,90'],
            'lng' => ['nullable', 'numeric', 'between:-180,180'],
            'meta_title' => ['nullable', 'string', 'max:70'],
            'meta_description' => ['nullable', 'string', 'max:200'],
            'og_image_url' => ['nullable', 'url', 'max:500'],
            'is_published' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'nearby' => ['array'],
            'nearby.*' => ['integer', 'exists:locations,id'],
            'services' => ['array'],
            'services.*' => ['integer', 'exists:services,id'],
        ];
    }
}
