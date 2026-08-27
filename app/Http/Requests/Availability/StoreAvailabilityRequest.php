<?php

namespace App\Http\Requests\Availability;

use Illuminate\Foundation\Http\FormRequest;

class StoreAvailabilityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slots'                    => ['required', 'array', 'min:1'],
            'slots.*.day_of_week'      => ['required', 'integer', 'between:0,6'],
            'slots.*.start_time'       => ['required', 'date_format:H:i'],
            'slots.*.end_time'         => ['required', 'date_format:H:i', 'after:slots.*.start_time'],
            'slots.*.is_available'     => ['nullable', 'boolean'],
        ];
    }
}
