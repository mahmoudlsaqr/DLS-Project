<?php

namespace App\Http\Traits\TablesBuilderTraits\WorkOrders;

use App\Models\Technicians;
use App\Models\User;
use App\Models\WorkOrdersManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

trait WorkOrdersManagementTable
{
    public function data(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

        $query = WorkOrdersManagement::orderByDesc("id");

        if ($user->hasRole('technician')) {
            $technician = Technicians::where('user_id', $userId)->first();

            if (!empty($technician)) {
                $technicianBranches = $technician->branches->pluck('id');

                $query->whereIn('branch_id', $technicianBranches);
            }
        } else {
            if (!empty($branchesUserIds) && !$user->hasRole('owner')) {
                $query->whereIn("branch_id", $branchesUserIds);
            }
        }

        $data = $query;

        if ($request ->ajax()) {
            try {
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->filter(function ($instance) use ($request) {
                        $search = trim($request->input('search'));
                        if (!empty($search)) {
                            $instance->where(function ($query) use ($search) {
                                $query->where('id', 'LIKE', "%$search%")
                                    ->orWhere('work_order_number', 'LIKE', "%$search%")
                                    ->orWhere('patient_name', 'LIKE', "%$search%")
                                    ->orWhere('file_number', 'LIKE', "%$search%")
                                    ->orWhereHas('colors', fn($q) => $q->where('color_code', 'LIKE', "%$search%"))
                                    ->orWhereHas('branches', fn($q) => $q->where('name_ar', 'LIKE', "%$search%")
                                        ->orWhere('name_en', 'LIKE', "%$search%"))
                                    ->orWhereHas('doctors', fn($q) => $q->where('name_ar', 'LIKE', "%$search%")
                                        ->orWhere('name_en', 'LIKE', "%$search%"));
                            });
                        }

                        $filters = [
                            'branch' => 'branch_id',
                            'client' => 'client_id',
                            'doctor' => 'doctor_id',
                            'service' => 'service_id',
                            'phase'  => 'phase_id',
                            'color'  => 'color_id',
                        ];

                        foreach ($filters as $key => $column) {
                            if (!empty($request->filled($key))) {
                                $instance->where($column, $request->input($key));
                            }
                        }

                        if ($request->filled(['dateFrom', 'dateTo'])) {
                            $instance->whereBetween('work_order_date', [$request->input('dateFrom'), $request->input('dateTo')]);
                        }
                    })
                    ->addColumn('checkbox', function($row) {
                        return '<div class="form-check form-switch d-flex justify-content-center align-items-center">
                                <input class="form-check-input h-20px w-20px p-0 child_check" data-id="'.$row["id"].'" type="checkbox" data-kt-check="true" />
                            </div>';
                    })
                    ->addColumn('work_order_date', function ($row) {
                        if (empty($row["work_order_date"])) { return ""; }
                        return (date('d-m-Y', strtotime($row["work_order_date"])));
                    })
                    ->addColumn('service', function ($row) {
                        return getLocaleName($row->services, "name_ar", "name_en");
                    })
                    ->addColumn('client', function ($row) {
                        return getLocaleName($row->clients, "name_ar", "name_en");
                    })
                    ->addColumn('color', fn($row) => optional($row->colors)->color_code ?? '')
                    ->addColumn('doctor', fn($row) => getLocaleName(optional($row->doctors), "name_ar", "name_en"))
                    ->addColumn('phase', function ($row) {
                        return getLocaleName($row->phases, "name_ar", "name_en");
                    })
                    ->addColumn('branch', function ($row) {
                        return getLocaleName($row->branches, "name_ar", "name_en");
                    })
                    ->addColumn('status', function($row) {
                        $isActive = $row->status === 'true';
                        return ' <div class="d-flex justify-content-start align-items-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input h-20px w-20px p-0" ' . ($isActive ? 'checked' : '') . '
                                        type="checkbox" name="status" value="' . $row->status . '"
                                        data-id="' . $row->id . '" id="userStatus' . $row->id . '"/>
                                    </div>
                                    <span class="badge ' . ($isActive ? 'badge-light-primary' : 'badge-light-danger') . '">
                                        ' . ($isActive ? __('Active') : __('Inactive')) . '
                                    </span>
                                </div>
                            ';
                    })
                    ->addColumn('created_by', function ($row) {
                        return WhoDidItAndWhen($row->created_at, $row->createdBy);
                    })
                    ->addColumn('updated_by', function ($row) {
                        return WhoDidItAndWhen($row->updated_at, $row->updatedBy);
                    })
                    ->addColumn('action', function($row) {
                        $name = getLocaleName($row, "name_ar", "name_en");
                        $buttons = '';

                        if (auth()->user()->can('log phases details_work_orders_management')) {
                            $buttons .= '<div class="menu-item px-2">
                                    <a class="menu-link px-3 view_details" data-id="' . $row["id"] . '">' . __("Log phases") . '</a>
                              </div>';
                        }

                        if (auth()->user()->can('update phase_work_orders_management')) {
                            $buttons .= '<div class="menu-item px-2">
                                    <a class="menu-link px-3 update_phase" data-id="' . $row["id"] . '">' . __("Update phase") . '</a>
                              </div>';
                        }

                        if (auth()->user()->can('print statement_work_orders_management')) {
                            $buttons .= '<div class="menu-item px-2">
                                    <a class="menu-link px-3 print_statement_button" data-id="' . $row["id"] . '">' . __("Print statement") . '</a>
                              </div>';
                        }

                        if (auth()->user()->can('print_work_orders_management')) {
                            $buttons .= '<div class="menu-item px-2">
                                    <a class="menu-link px-3 print_button" data-id="' . $row["id"] . '">' . __("Print barcode") . '</a>
                              </div>';
                        }

                        if (auth()->user()->can('view details_work_orders_management')) {
                            $buttons .= '<div class="menu-item px-2">
                                    <a class="menu-link px-3" href="'.route("work_orders.work_orders_management.show", [$row["id"]]).'" data-id="' . $row["id"] . '">' . __("View details") . '</a>
                              </div>';
                        }

                        if (auth()->user()->can('update_work_orders_management')) {
                            $buttons .= '<div class="menu-item px-2">
                                <a href="' . route("work_orders.work_orders_management.edit", [$row["id"]]) . '" class="menu-link px-3">' . __("Edit") . '</a>
                              </div>';
                        }

                        if (auth()->user()->can('remove_work_orders_management')) {
                            $buttons .= '<div class="menu-item px-2">
                                <a class="menu-link delete-confirm" data-id="' . $row["id"] . '" data-name="' . $name . '">' . __("Delete") . '</a>
                              </div>';
                        }

                        return '
                        <div class="btn-group dropstart">
                            <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical fs-3"></i>
                            </a>
                            <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-2" data-kt-menu="true">
                                ' . $buttons . '
                            </ul>
                        </div>';
                    })
                    ->rawColumns(['checkbox', 'work_order_date', 'created_by', 'status', 'action'])
                    ->make(true);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }

    }
}
