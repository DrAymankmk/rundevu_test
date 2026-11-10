<?php

namespace App\Http\Requests\Admin;

use App\Models\Deal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (int) auth()->user()->app_type === 6;
    }

    public function rules(): array
    {
        return [
            'title_ar' => ['required', 'string', 'max:255'],
            'title_en' => ['required', 'string', 'max:255'],
            'description_ar' => ['nullable', 'string'],
            'description_en' => ['nullable', 'string'],
            'conditions_ar' => ['nullable', 'string'],
            'conditions_en' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
            'clinic_id' => ['required', 'exists:clinics,id'],
            'specialty_id' => ['nullable', 'exists:specialties,id'],
            'price_before' => ['required', 'numeric', 'min:0'],
            'price_after' => ['required', 'numeric', 'min:0', 'lte:price_before'],
            'status' => ['required', Rule::in([Deal::STATUS_ACTIVE, Deal::STATUS_NOT_ACTIVE])],
            'maximum_number_of_usage' => ['nullable', 'integer', 'min:1'],
            'expiration_date' => ['nullable', 'date'],
        ];
    }
}
