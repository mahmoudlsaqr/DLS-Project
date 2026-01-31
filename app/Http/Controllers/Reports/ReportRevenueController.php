<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Doctors;
use App\Models\InvoicesPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportRevenueController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_revenue_report', ['only' => ['index', 'report']]);
        $this->middleware('permission:print_revenue_report', ['only' => ['print']]);
    }

    public function index()
    {
        $branches = Branch::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::where("status", "true")->select (['id', 'name_ar', 'name_en'])->get();
        return view("reports.revenue_report.index", [
            'branches' => $branches,
            'doctors' => $doctors,
        ]);
    }

    public function report(Request $request)
    {
        $branch = $request->branch_id;
        $doctor = $request->doctor_id;
        $dateFrom = $request->start_date;
        $dateTo = $request->end_date;

        $user = Auth::user();
        $branchesUserIds = $user->branches->pluck('id');

        $filter = InvoicesPayments::with(['invoicesMain', 'users'])
            ->whereHas('invoicesMain', function ($query) use ($branchesUserIds) {
            $query->whereIn('branch_id', $branchesUserIds);
        })->select('*');

        if ($branch) {
            $filter->whereHas('invoicesMain', function ($query) use ($request) {
                $query  ->where('branch_id', $request["branch_id"]);
            });
        }
        if ($doctor) {
            $filter->whereHas('invoicesMain', function ($query) use ($request) {
                $query  ->where('doctor_id', $request["doctor_id"]);
            });
        }
        if ($dateFrom && $dateTo) {
            $filter->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        $filter = $filter->get();
        $sumAmount = $filter->sum("amount");
        $sumCash = $filter->where("payment_type", 1)->sum("amount");
        $sumTransfer = $filter->where("payment_type", 2)->sum("amount");
        $sumCheck = $filter->where("payment_type", 3)->sum("amount");

        $branches = Branch::select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::select (['id', 'name_ar', 'name_en'])->get();

        return view("reports.revenue_report.index", [
            "workOrders" => $filter,
            "request" => $request,
            'branches' => $branches,
            'doctors' => $doctors,
            'sumAmount' => $sumAmount,
            'sumCash' => $sumCash,
            'sumTransfer' => $sumTransfer,
            'sumCheck' => $sumCheck,
        ]);
    }

    public function print(Request $request)
    {
        $branch = $request->branch_id;
        $doctor = $request->doctor_id;
        $dateFrom = $request->start_date;
        $dateTo = $request->end_date;

        $user = Auth::user();
        $branchesUserIds = $user->branches->pluck('id');

        $filter = InvoicesPayments::with(['invoicesMain', 'users'])
            ->whereHas('invoicesMain', function ($query) use ($branchesUserIds) {
                $query->whereIn('branch_id', $branchesUserIds);
            })->select('*');

        if ($branch) {
            $filter->whereHas('invoicesMain', function ($query) use ($request) {
                $query  ->where('branch_id', $request["branch_id"]);
            });
        }
        if ($doctor) {
            $filter->whereHas('invoicesMain', function ($query) use ($request) {
                $query  ->where('doctor_id', $request["doctor_id"]);
            });
        }
        if ($dateFrom && $dateTo) {
            $filter->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        $filter = $filter->get();
        $sumAmount = $filter->sum("amount");
        $sumCash = $filter->where("payment_type", 1)->sum("amount");
        $sumTransfer = $filter->where("payment_type", 2)->sum("amount");
        $sumCheck = $filter->where("payment_type", 3)->sum("amount");

        $branches = Branch::select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::select (['id', 'name_ar', 'name_en'])->get();

        return view("reports.revenue_report.print", [
            "workOrders" => $filter,
            "request" => $request,
            'branches' => $branches,
            'doctors' => $doctors,
            'sumAmount' => $sumAmount,
            'sumCash' => $sumCash,
            'sumTransfer' => $sumTransfer,
            'sumCheck' => $sumCheck,
        ]);
    }


}
