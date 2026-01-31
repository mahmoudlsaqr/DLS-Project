<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $this ->id,
            'email' => 'required|email|unique:users,email,' . $this ->id,
            'country_code' => 'required',
            'mobile' => 'required',
            'password' => 'nullable|min:4',
            'role' => 'required',
        ];
    }
}
