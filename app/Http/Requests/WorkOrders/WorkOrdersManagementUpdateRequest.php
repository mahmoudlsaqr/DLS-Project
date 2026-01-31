<?php

namespace App\Http\Requests\WorkOrders;

use Illuminate\Foundation\Http\FormRequest;

class WorkOrdersManagementUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'work_order_date' => 'required|date',
        ];
    }
}
