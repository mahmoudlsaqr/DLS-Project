<?php

namespace App\Http\Requests\Settings\RegionalSettings\Nationalities;

use Illuminate\Foundation\Http\FormRequest;

class NationalitiesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "Nat_Ar" => 'required|unique:nationalities,Nat_Ar,' . $this ->id,
            "Nat_En" => 'required|unique:nationalities,Nat_En,' . $this ->id,
        ];
    }
}
