<?php

namespace App\Http\Requests\Settings\DataSettings;

use Illuminate\Foundation\Http\FormRequest;

class DoctorsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:doctors,name_ar,' . $this ->id,
            "name_en" => 'required|unique:doctors,name_en,' . $this ->id,
            'mobile' => 'required|starts_with:05|max:10|min:10',
            'email' => 'required|email|unique:doctors,email,' . $this ->id,
            "client_id" => 'required|integer',
            "nationality_id" => 'required|integer',
            "branch_id" => 'required|integer',
        ];
    }
}
