<?php

namespace App\Http\Requests\Settings\RegionalSettings\Regions;

use Illuminate\Foundation\Http\FormRequest;
use function request;

class RegionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:regions,name_ar,'.$this ->id,
            "name_en" => 'required|unique:regions,name_en,'.$this ->id,
            'code' => 'required|max:2',
        ];
    }
}
