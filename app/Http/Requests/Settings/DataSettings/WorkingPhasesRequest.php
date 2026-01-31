<?php

namespace App\Http\Requests\Settings\DataSettings;

use Illuminate\Foundation\Http\FormRequest;

class WorkingPhasesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:working_phases,name_ar,' . $this ->id,
            "name_en" => 'required|unique:working_phases,name_en,' . $this ->id,
            'completion_percent' => 'required|numeric|max:100|min:0',
        ];
    }
}
