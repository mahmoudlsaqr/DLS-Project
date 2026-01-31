<?php

namespace App\Http\Requests\Settings\RegionalSettings\Cities;

use Illuminate\Foundation\Http\FormRequest;
use function request;

class CitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:cities,name_ar,'.$this ->id,
            "name_en" => 'required|unique:cities,name_en,'.$this ->id,
            'region_id' => 'required',
        ];
    }
}
