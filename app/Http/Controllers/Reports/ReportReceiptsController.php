<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\ReceiptVoucher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportReceiptsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_receipts_report', ['only' => ['index', 'report']]);
        $this->middleware('permission:print_receipts_report', ['only' => ['print']]);
    }

    public function index()
    {
        $user = Auth::user();
        $userBranchIds = $user->branches->pluck('id');
        $receipt = ReceiptVoucher::with('Cases')
            ->whereHas('Cases.Physician.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })->orWhereHas('Cases.Supervising.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })
            ->get();

        return view("reports.receipts_report.index", [
            "Users" => User::where('status', '=', 'true')->get(),
            "Receipt" => $receipt,
        ]);
    }

    public function report(Request $request)
    {
        $Designer = $request->designer;
        $Physician = $request->physician;
        $Supervising = $request->supervising;
        $Status = $request->status;
        $dateFrom = $request->start_date;
        $dateTo = $request->end_date;

        $user = Auth::user();
        $userBranchIds = $user->branches->pluck('id');

        $filter = ReceiptVoucher::with('Cases')
            ->whereHas('Cases.Physician.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })->orWhereHas('Cases.Supervising.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })->select("*");


        if ($Designer) {
            $filter->where('created_by', $Designer);
        }
        if ($Physician) {
            $filter->whereHas('Cases', function ($query) use ($request) {
                $query  ->where('physician', $request->physician);
            });
        }
        if ($Supervising) {
            $filter->whereHas('Cases', function ($query) use ($request) {
                $query  ->where('supervising', $request->supervising);
            });
        }
        if ($Status) {
            $filter->whereHas('Cases', function ($query) use ($request) {
                $query  ->where('status', $request->status);
            });
        }
        if ($dateFrom && $dateTo) {
            $filter->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        $filter = $filter->get();

        return view("reports.receipts_report.index", [
            "Users" => User::get(),
            "Receipt" => $filter,
            "request" => $request,
        ]);
    }

    public function print(Request $request)
    {
        $Designer = $request->designer;
        $Physician = $request->physician;
        $Supervising = $request->supervising;
        $Status = $request->status;
        $dateFrom = $request->start_date;
        $dateTo = $request->end_date;

        $user = Auth::user();
        $userBranchIds = $user->branches->pluck('id');

        $filter = ReceiptVoucher::with('Cases')
            ->whereHas('Cases.Physician.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })->orWhereHas('Cases.Supervising.branches', function ($query) use ($userBranchIds) {
                $query->whereIn('branches.id', $userBranchIds);
            })->select("*");

        if ($Designer) {
            $filter->where('created_by', $Designer);
        }
        if ($Physician) {
            $filter->whereHas('Cases', function ($query) use ($request) {
                $query  ->where('physician', $request->physician);
            });
        }
        if ($Supervising) {
            $filter->whereHas('Cases', function ($query) use ($request) {
                $query  ->where('supervising', $request->supervising);
            });
        }
        if ($Status) {
            $filter->whereHas('Cases', function ($query) use ($request) {
                $query  ->where('status', $request->status);
            });
        }
        if ($dateFrom && $dateTo) {
            $filter->whereBetween('created_at', [$dateFrom, $dateTo]);
        }

        $filter = $filter->get();

        $designer = !empty($Designer)? User::find($Designer) : null;
        $physician = !empty($Physician)? User::find($Physician) : null;
        $supervising = !empty($Supervising)? User::find($Supervising) : null;
        $date_from = getDateDMY($dateFrom);
        $date_to = getDateDMY($dateTo);

        return view("reports.receipts_report.print", [
            "Users" => User::get(),
            "Receipt" => $filter,
            "request" => $request,
            "designer" => $designer->name ?? __("All"),
            "physician" => $physician->name ?? __("All"),
            "supervising" => $supervising->name ?? __("All"),
            "date_from" => $date_from ?? __("Undefined"),
            "date_to" => $date_to ?? __("Undefined"),
        ]);
    }

}
