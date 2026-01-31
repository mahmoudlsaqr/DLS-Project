<?php

namespace App\Http\Requests\Settings\DataSettings;

use Illuminate\Foundation\Http\FormRequest;

class SectionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:sections,name_ar,' . $this ->id,
            "name_en" => 'required|unique:sections,name_en,' . $this ->id,
        ];
    }
}
