<?php

namespace App\Http\Requests\Settings\DataSettings;

use Illuminate\Foundation\Http\FormRequest;

class ServicesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:services,name_ar,' . $this ->id,
            "name_en" => 'required|unique:services,name_en,' . $this ->id,
            "service_code" => 'required|unique:services,service_code,' . $this ->id,
            "category_id" => 'required|integer',
            "service_cost" => 'required|numeric',
            "service_price" => 'required|numeric',
            "tax" => 'required|numeric',
            "branches" => 'required',
        ];
    }
}
