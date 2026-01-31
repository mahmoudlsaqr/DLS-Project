<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Clients;
use App\Models\Doctors;
use App\Models\Technicians;
use App\Models\WorkingPhases;
use App\Models\WorkOrdersManagement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportWorkOrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_work_orders_report', ['only' => ['index', 'report']]);
        $this->middleware('permission:print_work_orders_report', ['only' => ['print']]);
    }

    public function index()
    {
        $workOrders = WorkOrdersManagement::with('branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy')
            ->whereDate('work_order_date', Carbon::today())
            ->get();

        $totals = [];
        $totals['service_count'] = $workOrders->sum('service_count') ?? 0;
        $totals['service_total_net'] = number_format($workOrders->sum('service_total_net'), 2) ?? 0;
        $totals['service_tax_value'] = number_format($workOrders->sum('service_tax_value'), 2) ?? 0;
        $totals['service_total'] = number_format($workOrders->sum('service_total'), 2) ?? 0;

        $branches = Branch::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $clients = Clients::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $phases = WorkingPhases::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();

        return view("reports.work_orders_report.index", [
            "workOrders" => $workOrders,
            "totals" => $totals,
            'branches' => $branches,
            'clients' => $clients,
            'doctors' => $doctors,
            'phases' => $phases,
        ]);
    }

    public function report(Request $request)
    {
        $branch = $request->branch_id;
        $doctor = $request->doctor_id;
        $client = $request->client_id;
        $phase = $request->phase_id;
        $dateFrom = $request->start_date;
        $dateTo = $request->end_date;

        $filter = WorkOrdersManagement::with('branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy')->select("*");

        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

        if ($user->hasRole('technician')) {
            $technician = Technicians::where('user_id', $userId)->first();

            if (!empty($technician)) {
                $technicianBranches = $technician->branches->pluck('id');

                $filter->whereIn('branch_id', $technicianBranches);
            }
        } else {
            if (!empty($branchesUserIds) && !$user->hasRole('owner')) {
                $filter->whereIn("branch_id", $branchesUserIds);
            }
        }

        if ($branch) {
            $filter->where('branch_id', $branch);
        }
        if ($doctor) {
            $filter->where('doctor_id', $doctor);
        }
        if ($client) {
            $filter->where('client_id', $client);
        }
        if ($phase) {
            $filter->where('phase_id', $phase);
        }
        if ($dateFrom && $dateTo) {
            $filter->whereBetween('work_order_date', [$dateFrom, $dateTo]);
        }
        $filter = $filter->get();

        $totals = [];
        $totals['service_count'] = $filter->sum('service_count') ?? 0;
        $totals['service_total_net'] = number_format($filter->sum('service_total_net'), 2) ?? 0;
        $totals['service_tax_value'] = number_format($filter->sum('service_tax_value'), 2) ?? 0;
        $totals['service_total'] = number_format($filter->sum('service_total'), 2) ?? 0;

        $branches = Branch::select (['id', 'name_ar', 'name_en'])->get();
        $clients = Clients::select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::select (['id', 'name_ar', 'name_en'])->get();
        $phases = WorkingPhases::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();

        return view("reports.work_orders_report.index", [
            "workOrders" => $filter,
            "totals" => $totals,
            "request" => $request,
            'branches' => $branches,
            'clients' => $clients,
            'doctors' => $doctors,
            'phases' => $phases,
        ]);
    }

    public function print(Request $request)
    {
        $branchId = $request->branch_id;
        $doctorId = $request->doctor_id;
        $clientId = $request->client_id;
        $phaseId = $request->phase_id;
        $dateFrom = $request->start_date;
        $dateTo = $request->end_date;

        if (!empty($branchId)) {
            $branch = Branch::findOrFail($branchId);
        }
        if (!empty($doctorId)) {
            $doctor = Doctors::findOrFail($doctorId);
        }
        if (!empty($clientId)) {
            $client = Clients::findOrFail($clientId);
        }
        if (!empty($phaseId)) {
            $phase = WorkingPhases::findOrFail($phaseId);
        }

        $filter = WorkOrdersManagement::with('branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy')->select("*");

        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

        if ($user->hasRole('technician')) {
            $technician = Technicians::where('user_id', $userId)->first();

            if (!empty($technician)) {
                $technicianBranches = $technician->branches->pluck('id');

                $filter->whereIn('branch_id', $technicianBranches);
            }
        } else {
            if (!empty($branchesUserIds) && !$user->hasRole('owner')) {
                $filter->whereIn("branch_id", $branchesUserIds);
            }
        }

        if ($branchId) {
            $filter->where('branch_id', $branchId);
        }
        if ($doctorId) {
            $filter->where('doctor_id', $doctorId);
        }
        if ($clientId) {
            $filter->where('client_id', $clientId);
        }
        if ($phaseId) {
            $filter->where('phase_id', $phaseId);
        }
        if ($dateFrom && $dateTo) {
            $filter->whereBetween('work_order_date', [$dateFrom, $dateTo]);
        }
        $filter = $filter->get();

        $totals = [];
        $totals['service_count'] = $filter->sum('service_count') ?? 0;
        $totals['service_total_net'] = number_format($filter->sum('service_total_net'), 2) ?? 0;
        $totals['service_tax_value'] = number_format($filter->sum('service_tax_value'), 2) ?? 0;
        $totals['service_total'] = number_format($filter->sum('service_total'), 2) ?? 0;

        return view("reports.work_orders_report.print", [
            "workOrders" => $filter,
            "totals" => $totals,
            "branch" => isset($branch)? getLocaleName($branch, "name_ar", "name_en") : __("All Branches"),
            "doctor" => isset($doctor)? getLocaleName($doctor, "name_ar", "name_en") : __("All Doctors"),
            "client" => isset($client)? getLocaleName($client, "name_ar", "name_en") : __("All Clients"),
            "phase" => isset($phase)? getLocaleName($phase, "name_ar", "name_en") : __("All Phasses"),
            "date_from" => $dateFrom ?? __("Undefined"),
            "date_to" => $dateTo ?? __("Undefined"),
        ]);
    }

}
