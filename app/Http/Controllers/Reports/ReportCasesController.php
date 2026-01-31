<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportCasesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_cases_report', ['only' => ['index', 'report']]);
        $this->middleware('permission:print_cases_report', ['only' => ['print']]);
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $userBranchIds = $user->branches->pluck('id');
        $cases = Cases::with('Physician', 'Supervising')
            ->whereHas('Physician.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })->orWhereHas('Supervising.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })
            ->get();

        return view("reports.cases_report.index", [
            "Users" => User::where('status', '=', 'true')->get(),
            "Cases" => $cases,
        ]);
    }

    public function report(Request $request)
    {
        $Designer = $request->designer;
        $Physician = $request->physician;
        $Supervising = $request->supervising;
        $Status = $request->status;
        $DateFrom = $request->start_date;
        $DateTo = $request->end_date;

        $user = Auth::user();
        $userBranchIds = $user->branches->pluck('id');
        $filter = Cases::with('Physician', 'Supervising')
            ->whereHas('Physician.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })->orWhereHas('Supervising.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })
            ->select("*");

        if ($Designer) {
            $filter->where('created_by', $Designer);
        }
        if ($Physician) {
            $filter ->where('physician', $Physician);
        }
        if ($Supervising) {
            $filter ->where('supervising', $Supervising);
        }
        if ($Status) {
            $filter->where('status', $Status);
        }
        if ($DateFrom && $DateTo) {
            $filter->whereBetween('created_at', [$DateFrom, $DateTo]);
        }
        $filter = $filter->get();

        return view("reports.cases_report.index", [
            "Users" => User::get(),
            "Cases" => $filter,
            "request" => $request,
        ]);
    }

    public function print(Request $request)
    {
        $Designer = $request->designer;
        $Physician = $request->physician;
        $Supervising = $request->supervising;
        $Status = $request->status;
        $DateFrom = $request->start_date;
        $DateTo = $request->end_date;

        $user = Auth::user();
        $userBranchIds = $user->branches->pluck('id');
        $filter = Cases::with('Physician', 'Supervising')
            ->whereHas('Physician.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })->orWhereHas('Supervising.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })
            ->select("*");

        if ($Designer) {
            $filter->where('created_by', $Designer);
        }
        if ($Physician) {
            $filter ->where('physician', $Physician);
        }
        if ($Supervising) {
            $filter ->where('supervising', $Supervising);
        }
        if ($Status) {
            $filter->where('status', $Status);
        }
        if ($DateFrom && $DateTo) {
            $filter->whereBetween('created_at', [$DateFrom, $DateTo]);
        }
        $filter = $filter->get();

        $designer = !empty($Designer)? User::find($Designer) : null;
        $physician = !empty($Physician)? User::find($Physician) : null;
        $supervising = !empty($Supervising)? User::find($Supervising) : null;
        $date_from = getDateDMY($DateFrom);
        $date_to = getDateDMY($DateTo);

        return view("reports.cases_report.print", [
            "Users" => User::get(),
            "Cases" => $filter,
            "request" => $request,
            "designer" => $designer->name ?? __("All"),
            "physician" => $physician->name ?? __("All"),
            "supervising" => $supervising->name ?? __("All"),
            "date_from" => $date_from ?? __("Undefined"),
            "date_to" => $date_to ?? __("Undefined"),
        ]);
    }

}
