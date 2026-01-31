<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Clients;
use App\Models\Doctors;
use App\Models\Technicians;
use App\Models\WorkingPhases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportWorkPhasesStatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_work_phases_statistics_report', ['only' => ['index', 'report']]);
    }

    public function index()
    {
        $workPhases = WorkingPhases::where("status", "true")->get();

        $branches = Branch::where("status", "true")->select (['id', 'name_ar', 'name_en']);

        $clients = Clients::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $phases = WorkingPhases::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();

        return view("reports.work_phases_statistics_report.index", [
            "workPhases" => $workPhases,
            'branches' => $branches->get(),
            'clients' => $clients,
            'doctors' => $doctors,
            'phases' => $phases,
        ]);
    }

    public function report(Request $request)
    {
        $workPhases = WorkingPhases::where("status", "true")->get();

        $branches = Branch::where("status", "true")->select (['id', 'name_ar', 'name_en']);

        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

        if ($user->hasRole('technician')) {
            $technician = Technicians::where('user_id', $userId)->first();

            if (!empty($technician)) {
                $technicianBranches = $technician->branches->pluck('id');

                $branches->whereIn('id', $technicianBranches);
            }
        } else {
            if (!empty($branchesUserIds) && !$user->hasRole('owner')) {
                $branches->whereIn("id", $branchesUserIds);
            }
        }

        $clients = Clients::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $phases = WorkingPhases::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();

        return view("reports.work_phases_statistics_report.index", [
            "request" => $request,
            "workPhases" => $workPhases,
            'branches' => $branches->get(),
            'clients' => $clients,
            'doctors' => $doctors,
            'phases' => $phases,
        ]);
    }
}
