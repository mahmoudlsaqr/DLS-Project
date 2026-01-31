<?php

namespace App\Http\Traits\TablesBuilderTraits\Invoices;

use App\Models\InvoicesMain;
use App\Models\Technicians;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

trait InvoicesTable
{
    public function data(Request $request)
    {

        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

        $query = InvoicesMain::with("workOrders", "branches", "clients", "doctors", "createdBy")->select('*');

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
        $data = $query->orderBy('id', 'desc');

        if ($request->ajax()) {
            try {
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->filter(function ($instance) use ($request) {
                        $search = $request->input('search');
                        if ($request->has('search') && !empty($search)) {
                            $instance ->where('id', 'LIKE', '%' . $search . '%');
                        }
                        if ($request->input('branch')) {
                            $instance->where('branch_id', $request->input('branch'));
                        }
                        if ($request->input('client')) {
                            $instance->where('client_id', $request->input('client'));
                        }
                        if ($request->input('doctor')) {
                            $instance->where('doctor_id', $request->input('doctor'));
                        }
                        if ($request ->get('dateFrom') and $request ->get('dateTo')) {
                            $instance ->whereBetween('work_order_date', [request('dateFrom'), request('dateTo')]);
                        }
                    })
                    ->addColumn('invoice_date', function($row) {
                        return date("d-m-Y", strtotime($row->invoice_date));
                    })
                    ->addColumn('branch', function($row) {
                        return getLocaleName($row->branches, "name_ar", "name_en");
                    })
                    ->addColumn('client', function($row) {
                        return getLocaleName($row->clients, "name_ar", "name_en");
                    })
                    ->addColumn('doctor', function($row) {
                        return getLocaleName($row->doctors, "name_ar", "name_en");
                    })
                    ->addColumn('action', function($row) {
                        $buttons = '';
                        if (auth()->user()->can('view details_invoices')) {
                            $buttons .= '<div class="menu-item px-2">
                                <a class="menu-link px-3" href="'.route("invoices.invoices_list.show", [$row["id"]]).'" data-id="' . $row["id"] . '">' . __("View details") . '</a>
                            </div>';
                        }

                        if (auth()->user()->can('print_invoices')) {
                            $buttons .= '<div class="menu-item px-2">
                               <a data-id="' . $row["id"] . '" class="menu-link px-3 print_button">' . __("Print") . '</a>
                            </div>';
                        }
                        if (auth()->user()->can('pay_invoices') and (float)$row["total_paid"] < (float)$row["total_total"]) {
                            $buttons .= '<div class="menu-item px-2">
                                <a class="menu-link pay_button" data-id="' . $row["id"] . '">' . __("Pay") . '</a>
                            </div>';
                        }

                        return '
                        <div class="btn-group dropstart">
                            <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical fs-3"></i>
                            </a>
                            <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-2" data-kt-menu="true">
                                ' . $buttons . '
                            </ul>
                        </div>';
                    })
                    ->rawColumns(['action', 'invoice_date', 'branch', 'client', 'doctor'])
                    ->make(true);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }

        return view("invoices.invoices_list.index", [
            "data" => $data->get(),
        ]);
    }
}
