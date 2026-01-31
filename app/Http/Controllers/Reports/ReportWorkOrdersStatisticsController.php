<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Clients;
use App\Models\Doctors;
use App\Models\Technicians;
use App\Models\WorkingPhases;
use App\Models\WorkOrdersManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportWorkOrdersStatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_work_orders_statistics_report', ['only' => ['index', 'report']]);
        $this->middleware('permission:print_work_orders_statistics_report', ['only' => ['print']]);
    }

    public function index()
    {
        $today = now()->toDateString();
        $query = WorkOrdersManagement::selectRaw('work_order_date, COUNT(*) as total_work_orders')
            ->whereDate('work_order_date', $today)
            ->groupBy('work_order_date');

        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

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

        $branches = Branch::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $clients = Clients::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $phases = WorkingPhases::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();

        return view("reports.work_orders_statistics_report.index", [
            "workOrders" => $query->get(),
            'branches' => $branches,
            'clients' => $clients,
            'doctors' => $doctors,
            'phases' => $phases,
        ]);
    }

    public function report(Request $request)
    {
        $today = now()->toDateString();
        $branch = $request->branch_id;
        $doctor = $request->doctor_id;
        $client = $request->client_id;
        $phase = $request->phase_id;
        $dateFrom = $request->start_date;
        $dateTo = $request->end_date;

        $filter = WorkOrdersManagement::query();

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
            $filter->whereBetween('work_order_date', [$dateFrom, $dateTo])->selectRaw('work_order_date, COUNT(*) as total_work_orders')->groupBy('work_order_date');
        } else {
            $filter->selectRaw('work_order_date, COUNT(*) as total_work_orders')
                ->whereDate('work_order_date', $today)->groupBy('work_order_date');
        }

        $workOrders = $filter->get();

        $branches = Branch::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $clients = Clients::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $phases = WorkingPhases::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();

        return view("reports.work_orders_statistics_report.index", [
            "request" => $request,
            "workOrders" => $workOrders,
            'branches' => $branches,
            'clients' => $clients,
            'doctors' => $doctors,
            'phases' => $phases,
        ]);
    }

    public function print(Request $request)
    {
        $branch = $request->branch_id;
        $doctor = $request->doctor_id;
        $client = $request->client_id;
        $phase = $request->phase_id;
        $dateFrom = $request->start_date;
        $dateTo = $request->end_date;

        $filter = WorkOrdersManagement::query();

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
            $filter->whereBetween('work_order_date', [$dateFrom, $dateTo])->selectRaw('work_order_date, COUNT(*) as total_work_orders')->groupBy('work_order_date');
        }

        $workOrders = $filter->get();

        $branches = Branch::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $clients = Clients::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $phases = WorkingPhases::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();

        return view("reports.work_orders_statistics_report.print", [
            "request" => $request,
            "workOrders" => $workOrders,
            'branches' => $branches,
            'clients' => $clients,
            'doctors' => $doctors,
            'phases' => $phases,
        ]);
    }

}
