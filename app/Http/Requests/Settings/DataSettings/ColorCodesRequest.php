<?php

namespace App\Http\Requests\Settings\DataSettings;

use Illuminate\Foundation\Http\FormRequest;

class ColorCodesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "color_code" => 'required|unique:color_codes,color_code,' . $this ->id,
        ];
    }
}
