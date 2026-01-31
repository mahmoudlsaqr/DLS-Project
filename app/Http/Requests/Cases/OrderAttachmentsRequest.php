<?php

namespace App\Http\Requests\Cases;

use Illuminate\Foundation\Http\FormRequest;

class OrderAttachmentsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
           'attachments_repeater.*.type' => 'required',
           'attachments_repeater.*.file' => 'required|mimes:jpg,jpeg,png,mp4,wmv,mpeg,mpg,avi,pdf,stl,tl,bin',
        ];
    }
}
