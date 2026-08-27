<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id'       => ['required', 'exists:service_categories,id'],
            'title'             => ['required', 'string', 'max:255'],
            'description'       => ['required', 'string'],
            'price'             => ['required', 'numeric', 'min:0'],
            'price_type'        => ['required', Rule::in(['fixed', 'hourly'])],
            'duration_minutes'  => ['nullable', 'integer', 'min:1'],
            'image'             => ['nullable', 'string'],
            'status'            => ['nullable', Rule::in(['active', 'inactive'])],
        ];
    }
}
