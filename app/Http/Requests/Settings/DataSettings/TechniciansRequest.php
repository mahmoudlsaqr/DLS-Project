<?php

namespace App\Http\Requests\Settings\DataSettings;

use App\Models\Technicians;
use Illuminate\Foundation\Http\FormRequest;

class TechniciansRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name_ar" => 'required|unique:technicians,name_ar,' . $this ->id,
            "name_en" => 'required|unique:technicians,name_en,' . $this ->id,
            'mobile' => 'required|unique:technicians,mobile,' . $this ->id,
            'email' => 'required|email|unique:technicians,email,' . $this ->id,
            'username' => 'required|string',
            'password' => 'required|min:4',
            "section_id" => 'required|integer',
            "nationality_id" => 'required|integer',
            "branches" => 'required',
            "phases" => 'required',
        ];
    }
}
