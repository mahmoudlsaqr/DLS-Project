<?php

namespace App\Http\Requests\Settings\DataSettings;

use Illuminate\Foundation\Http\FormRequest;

class SpecialOffersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'offer_name' => 'required|string|max:255|unique:special_offers,offer_name,' . $this->id,
            'doctor_id' => 'required|integer|exists:doctors,id|unique:special_offers,doctor_id,' . $this->id,
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'discount' => 'required|numeric|between:1,100',
        ];
    }
}
