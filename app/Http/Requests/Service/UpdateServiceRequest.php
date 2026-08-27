<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'       => ['sometimes', 'exists:service_categories,id'],
            'title'             => ['sometimes', 'string', 'max:255'],
            'description'       => ['sometimes', 'string'],
            'price'             => ['sometimes', 'numeric', 'min:0'],
            'price_type'        => ['sometimes', Rule::in(['fixed', 'hourly'])],
            'duration_minutes'  => ['nullable', 'integer', 'min:1'],
            'image'             => ['nullable', 'string'],
            'status'            => ['nullable', Rule::in(['active', 'inactive'])],
        ];
    }
}
