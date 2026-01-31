<?php

namespace App\Http\Requests\Settings\RegionalSettings\Districts;

use Illuminate\Foundation\Http\FormRequest;
use function request;

class DistrictsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:districts,name_ar,'.$this ->id,
            "name_en" => 'required|unique:districts,name_en,'.$this ->id,
            'region_id' => 'required',
            'city_id' => 'required',
        ];
    }
}
