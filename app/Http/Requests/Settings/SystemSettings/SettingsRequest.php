<?php

namespace App\Http\Requests\Settings\SystemSettings;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'center_admin_mobile' => 'nullable',
            'center_mobile' => 'nullable',
            'center_phone' => 'nullable',
            "center_email" => 'nullable|email',
            "center_admin_email" => 'nullable|email',
            'additional_mobile_1' => 'nullable',
            'additional_mobile_2' => 'nullable',
            'additional_mobile_3' => 'nullable',
            'additional_mobile_4' => 'nullable',
        ];
    }
}
