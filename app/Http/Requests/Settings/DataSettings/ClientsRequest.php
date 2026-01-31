<?php

namespace App\Http\Requests\Settings\DataSettings;

use Illuminate\Foundation\Http\FormRequest;

class ClientsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:clients,name_ar,' . $this ->id,
            "name_en" => 'required|unique:clients,name_en,' . $this ->id,
            'tax_number' => 'required',
            'region_id' => 'required',
            'city_id' => 'required',
            'branch_id' => 'required',
            'discount' => 'required',
            'mobile' => 'required|starts_with:05|max:10|min:10',
            'phone' => 'nullable|starts_with:01|max:10|min:10',
            "longitude" => 'nullable|max:10',
            "latitude" =>  'nullable|max:10',
        ];
    }
}
