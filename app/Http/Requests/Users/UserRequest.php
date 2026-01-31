<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'password' => 'required|min:4',
            'role' => 'required',
        ];
    }
}
