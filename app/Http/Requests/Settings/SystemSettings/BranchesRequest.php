<?php

namespace App\Http\Requests\Settings\SystemSettings;

use Illuminate\Foundation\Http\FormRequest;

class BranchesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:branches,name_ar,' . $this ->id,
            "name_en" => 'required|unique:branches,name_en,' . $this ->id,
            "code" => 'required|max:10',
            "tin" => 'required|integer|ends_with:00003|min:15',
            'region_id' => 'required',
            'city_id' => 'required',
            'mobile' => 'required|starts_with:05|max:10|min:10',
            'phone' => 'required|starts_with:01|max:10|min:10',
            "address" => 'required',
            "longitude" => 'required|max:10',
            "latitude" =>  'required|max:10',
            "com_reg" =>  'required|max:10',
        ];
    }
}
