<?php

namespace App\Http\Requests\WorkOrders;

use Illuminate\Foundation\Http\FormRequest;

class WorkOrdersManagementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'work_order_number' => 'required|regex:/^[a-zA-Z0-9]+$/|unique:work_orders_management,work_order_number,' . $this ->id,
            'work_order_date' => 'required|date',
            'branch_id' => 'required|exists:branches,id',
            'doctor_id' => 'required|exists:doctors,id',
            'color_id' => 'required|exists:color_codes,id',
            'service_id' => 'required|exists:services,id',
            'service_count' => 'required|integer|min:1',
            'patient_name' => 'required',
            'statement' => 'required',
            'file_number' => 'required',
        ];
    }
}
