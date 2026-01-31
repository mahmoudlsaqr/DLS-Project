<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Http\Traits\TablesBuilderTraits\Invoices\InvoicesTable;
use App\Models\InvoicesDetails;
use App\Models\InvoicesMain;
use App\Models\InvoicesPayments;
use App\Models\Technicians;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:issue_invoices', ['only' => ['index']]);
        $this->middleware('permission:view details_invoices', ['only' => ['show']]);
        $this->middleware('permission:pay_invoices', ['only' => ['getAmounts', 'pay']]);
    }

    use InvoicesTable;

    public function index()
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

        return view("invoices.invoices_list.index", [
            "data" => $data->limit(50)->get(),
        ]);
    }

    public function show(int $id)
    {
        $dataInvoice = InvoicesMain::find($id);
        $dataInvoiceDetails = InvoicesDetails::where('invoice_id', $id)->get();
        $dataInvoicePayment = InvoicesPayments::where('invoice_id', $id)->get();

        return view("invoices.invoices_list.show", [
            'invoice' => $dataInvoice,
            'invoiceDetails' => $dataInvoiceDetails,
            'invoicePayment' => $dataInvoicePayment,
        ]);
    }

    public function pay(Request $request)
    {
        try {
            $request->validate([
                'payment_type' => 'required',
                'amount' => 'required|numeric|min:0',
            ]);

            $id = $request['id'];
            $invoiceMain = InvoicesMain::find($id);
            $total = (float)$invoiceMain['total_total'];
            $totalPaidInv = (float)$invoiceMain['total_paid'];
            $totalResidualInv = (float)$invoiceMain['total_residual'];

            $amountRequest = (float)$request['amount'];
            if ($totalPaidInv === 0.00) {
                $amount = $amountRequest;
                if ($amount > $total) {
                    return response() ->json(['error' => __("Count should be less than:"). " " . $total ]);
                }
            } else {
                $amount = $totalPaidInv + $amountRequest;
                if ($amount > $total) {
                    return response() ->json(['error' => __("Count should be less than:"). " " . $totalResidualInv ]);
                }
            }

            $invoicePayment = InvoicesPayments::create([
                'invoice_id' => $id,
                'payment_type' => $request['payment_type'],
                'amount' => $request['amount'],
                'reference' => $request['reference'] ?? "",
                'note' => $request['note'] ?? "",
                'user_id' => Auth::id(),
            ]);

            $totalPaid = (float)$invoicePayment['amount'] + $totalPaidInv;
            $totalResidual = $total - $totalPaid;
            $invoiceMain->update([
                'total_paid' => $totalPaid,
                'total_residual' => $totalResidual,
            ]);

            return response() ->json([ 'success' => __("Payment has been made successfully") ]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }
}
