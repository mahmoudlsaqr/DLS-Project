<?php

namespace App\Http\Requests\Cases;

use Illuminate\Foundation\Http\FormRequest;

class CaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "case_number" => 'required|unique:cases,case_number,'.$this ->id,
            "patient_name" => 'required',
            "physician" => 'required',
            "note" => 'required',
            "age" => 'nullable|numeric|between:1,100',
        ];
    }
}
