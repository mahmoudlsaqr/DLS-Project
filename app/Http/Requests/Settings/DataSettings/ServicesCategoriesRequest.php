<?php

namespace App\Http\Requests\Settings\DataSettings;

use Illuminate\Foundation\Http\FormRequest;

class ServicesCategoriesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:services_categories,name_ar,' . $this ->id,
            "name_en" => 'required|unique:services_categories,name_en,' . $this ->id,
            "category_code" => 'required|max:3|unique:services_categories,category_code,' . $this ->id,
        ];
    }
}
