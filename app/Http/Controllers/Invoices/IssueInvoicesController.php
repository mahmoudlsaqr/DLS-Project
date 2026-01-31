<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Clients;
use App\Models\Doctors;
use App\Models\InvoicesDetails;
use App\Models\InvoicesMain;
use App\Models\InvoicesPayments;
use App\Models\Technicians;
use App\Models\WorkOrdersManagement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Prgayman\Zatca\Utilis\QrCodeOptions;
use Prgayman\Zatca\Facades\Zatca;

class IssueInvoicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:issue_invoices', ['only' => ['index', 'search', 'issue']]);
        $this->middleware('permission:print_invoices', ['only' => ['print']]);
    }

    public function index()
    {
        $branches = Branch::select (['id', 'name_ar', 'name_en'])->get();
        $clients = Clients::select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::select(['id', 'name_ar', 'name_en'])->get();

        return view('invoices.issue_invoice.index', [
            'branches' => $branches,
            'clients' => $clients,
            'doctors' => $doctors,
        ]);
    }

    public function search(Request $request)
    {
        $branches = Branch::select (['id', 'name_ar', 'name_en'])->get();
        $clients = Clients::select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::select (['id', 'name_ar', 'name_en'])->get();

        $branch = $request->branch_id ?? "";
        $doctor = $request->doctor_id ?? "";
        $client = $request->client_id ?? "";
        $dateFrom = $request->start_date ?? "";
        $dateTo = $request->end_date ?? "";

        $query = WorkOrdersManagement::with('branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy')->select("*");

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

        if ($branch) {
            $query->where('branch_id', $branch);
        }
        if ($doctor) {
            $query->where('doctor_id', $doctor);
        }
        if ($client) {
            $query->where('client_id', $client);
        }
        if ($dateFrom && $dateTo) {
            $query->whereBetween('work_order_date', [$dateFrom, $dateTo]);
        }

        $filter = $query->whereNotIn('id', function($query) {
            $query->select('work_order_id')
                ->from('many_work_orders_invoices');
        })->get();

        $sumCount = $filter->sum('service_count');
        $sumPrice = $filter->sum('service_price');
        $sumDiscountValue = $filter->sum('service_discount_value');
        $sumNet = $filter->sum('service_total_net');
        $sumTaxValue = $filter->sum('service_tax_value');
        $sumTotal = $filter->sum('service_total');

        $total = [
            'sum_count' => $sumCount,
            'sum_price' => $sumPrice,
            'sum_discount_value' => $sumDiscountValue,
            'sum_net' => $sumNet,
            'sum_tax_value' => $sumTaxValue,
            'sum_total' => $sumTotal,
        ];

        return view("invoices.issue_invoice.index", [
            "workOrders" => $filter,
            "request" => $request,
            'branches' => $branches,
            'clients' => $clients,
            'doctors' => $doctors,
            'total' => $total,
        ]);
    }

    public function issue(Request $request)
    {
        try {
            $request->validate([
                'branch_id' => 'required|integer',
                'doctor_id' => 'required|integer',
                'client_id' => 'required|integer',
                'start_date' => 'required|date',
                'end_date' => 'required|date',
            ]);

            $branch = $request->branch_id ?? null;
            $doctor = $request->doctor_id ?? null;
            $client = $request->client_id ?? null;
            $additionalDiscount = $request->additional_discount ?? "";
            $additionalPercentDiscount = $request->additional_percent_discount ?? "";
            $dateFrom = $request->start_date ?? null;
            $dateTo = $request->end_date ?? null;

            $query = WorkOrdersManagement::with('branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy')->select("*");

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

            if (!empty($branch)) {
                $query->where('branch_id', $branch);
            }
            if (!empty($doctor)) {
                $query->where('doctor_id', $doctor);
            }
            if (!empty($client)) {
                $query->where('client_id', $client);
            }
            if (!empty($dateFrom) && !empty($dateTo)) {
                $query->whereBetween('work_order_date', [$dateFrom, $dateTo]);
            }

            $filter = $query->whereNotIn('id', function($query) {
                $query->select('work_order_id')->from('many_work_orders_invoices');
            });

            $workOrderIdsToArray = $filter->pluck("id")->toArray();
            $filter = $filter->get();
            if (count($filter) > 0) {
                $sumCount = $filter->sum('service_count');
                $sumPrice = $filter->sum('services_price');
                if (!empty($additionalPercentDiscount)) {
                    $discountValue = ((float)$additionalPercentDiscount * $sumPrice) / 100;
                } else {
                    $discountValue = (float)$additionalDiscount;
                }
                $sumDiscountValue = $filter->sum('services_discount_value') + $discountValue;
                $sumNet = $filter->sum('service_total_net');
                $sumTaxValue = $filter->sum('service_tax_value');
                $sumTotal = $filter->sum('service_total');

                $total = [
                    'total_count' => $sumCount,
                    'total_price' => $sumPrice,
                    'total_discount_value' => $sumDiscountValue,
                    'total_total_net' => $sumPrice - $sumDiscountValue,
                    'total_tax_value' => $sumTaxValue,
                    'total_total' => ($sumPrice - $sumDiscountValue) + $sumTaxValue,
                ];

                $vatRegistrationNumber = Branch::find($request["branch_id"])->tin;
                $totalWithVat = $total["total_total"];
                $vatTotal = $total["total_tax_value"];

                $qrCodeOptions = new QrCodeOptions;
                $qrCodeOptions->format("svg");
                $qrCodeOptions->color(255, 0, 0, 1);
                $qrCodeOptions->backgroundColor(0, 0, 0);
                $qrCodeOptions->size(100);
                $qrCodeOptions->margin(0);
                $qrCodeOptions->style('square', 0.5);
                $qrCodeOptions->eye('square');

                $base64 = Zatca::sellerName(settings("center_name_ar"))
                    ->vatRegistrationNumber($vatRegistrationNumber)
                    ->timestamp(Carbon::now())
                    ->totalWithVat($totalWithVat)
                    ->vatTotal($vatTotal)
                    ->toBase64($qrCodeOptions);

                $dataInvoice = InvoicesMain::create([
                    'qr_code' => $base64,
                    'invoice_date' => Carbon::now()->format('Y-m-d'),
                    'invoice_time' => Carbon::now()->format('H:i:s'),
                    'branch_id' => $branch,
                    'client_id' => $client,
                    'doctor_id' => $doctor,
                    'total_count' => $total["total_count"],
                    'total_price' => $total["total_price"],
                    'total_discount_value' => $total["total_discount_value"],
                    'additional_discount' => (float)$additionalDiscount,
                    'total_total_net' => $total["total_total_net"],
                    'total_tax_value' => $total["total_tax_value"],
                    'total_total' => $total["total_total"],
                    'total_paid' => 0,
                    'total_residual' => $total["total_total"],
                    'start_date_issue' => $dateFrom,
                    'end_date_issue' => $dateTo,
                    'created_by' => Auth::id(),
                ]);

                if (!empty($workOrderIdsToArray)) {
                    $workOrders = $workOrderIdsToArray;
                    $dataInvoice->workOrders()->sync($workOrders);
                }

                foreach ($filter as $row) {
                    InvoicesDetails::create([
                        'invoice_id' => $dataInvoice->id,
                        'work_order_id' => $row["id"],
                        'file_number' => $row["file_number"],
                        'patient_name' => $row["patient_name"],
                        'service_id' => $row["service_id"],
                        'color_id' => $row["color_id"],
                        'service_count' => $row["service_count"],
                        'service_price' => $row["service_price"],
                        'service_discount' => $row["service_discount"],
                        'service_discount_value' => $row["service_discount_value"],
                        'service_total_net' => $row["service_total_net"],
                        'service_tax' => $row["service_tax"],
                        'service_tax_value' => $row["service_tax_value"],
                        'service_total' => $row["service_total"],
                    ]);
                }

                return response()->json([
                    'invoiceId' => $dataInvoice['id'] ?? null,
                    'success' => __("Invoice was issued successfully")
                ]);
            } else {
                return response()->json([
                    'success' => __("Invoice was issued successfully")
                ]);
            }

        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function print(int $id)
    {
        $dataInvoice = InvoicesMain::find($id);
        $dataInvoiceDetails = InvoicesDetails::with("invoicesMain")->where('invoice_id', $id)->get();
        $dataInvoicePayment = InvoicesPayments::where('invoice_id', $id)->get();

        return view("invoices.invoices_list.print", [
            'invoice' => $dataInvoice,
            'invoiceDetails' => $dataInvoiceDetails,
            'invoicePayment' => $dataInvoicePayment,
        ]);
    }
}
