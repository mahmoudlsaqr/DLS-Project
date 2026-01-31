<?php

namespace App\Http\Requests\Cases;

use Illuminate\Foundation\Http\FormRequest;

class OrdersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "patient_name" => 'required',
            "required_in_case" => 'required',
            "treatment_plan" => 'required',
            "patient_expectations" => 'required',
            "age" => 'nullable|numeric|between:1,100',
        ];
    }
}
