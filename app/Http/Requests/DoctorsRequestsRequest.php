<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DoctorsRequestsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'doctor_name'       => 'required|string|max:255',
            'patient_name'      => 'required|string|max:255',
            'file_number'       => 'required|string|max:100',
            'type_case'         => 'required|string|max:255',
            'number_teeth'      => 'required|integer',
            'color_code'        => 'required|string|max:50',
            'duration_delivery' => 'required|string',
            'crowns'            => 'nullable|boolean',
            'bridges'           => 'nullable|boolean',
            'rpd'               => 'nullable|boolean',
            'rcd'               => 'nullable|boolean',
            'request_status'    => 'required|in:1,2',
            'notes'             => 'nullable|string',
            'delivered_by'      => 'nullable|exists:users,id',
            'delivered_at'      => 'nullable|date',
        ];
    }
}
