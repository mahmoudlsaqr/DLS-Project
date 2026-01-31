<?php

namespace App\Http\Traits\TablesBuilderTraits\Cases;

use App\Models\Cases;
use App\Models\ReceiptVoucher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

trait ReceiptVouchersTable
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = ReceiptVoucher::with("Cases")->select("*");
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->filter(function ($instance) use ($request) {
                        $search = $request->input('search');
                        if ($request->input('search')) {
                            $instance->where('id', 'LIKE', "%" . $search . "%")
                            ->orwhere('clinic_name', 'LIKE', "%" . $search . "%")
                            ->orwhere('amount', 'LIKE', $search . "%")
                            ->orwhereHas('Cases', function ($query) use ($request) {
                                $query  ->where('case_number', 'LIKE', "%{$request ->input('search')}")
                                        ->orwhere('patient_name', 'LIKE', "%{$request ->input('search')}");
                            });
                        }
                        if ($request->input('physician')) {
                            $instance ->orwhereHas('Cases', function ($query) use ($request) {
                                $query ->where('physician', $request->input('physician'));
                            });
                        }
                    })
                    ->addColumn('id', function($row) {
                        return '<div>'.$row["id"].'</div>';
                    })
                    ->addColumn('case_number', function ($row) {
                        return ($row["case_id"] != null)? $row ->Cases ->case_number : "" ;
                    })
                    ->addColumn('physician', function ($row) {
                        return ($row["case_id"] != null)? User::find($row ->Cases ->physician)->name : "" ;
                    })
                    ->addColumn('created_by_at', function ($row) {
                        if ($row->created_by != null) {
                            $user = User::find($row->created_by)->name;
                            $dateTime = $row->created_at;
                            $result = '
                                <span class="text-info">' . $user . '</span><br>
                                <span>' . $dateTime->format('d-m-Y') . '</span><br>
                                <span>' . $dateTime->format('h:i') . '</span>
                                <span>' . (($dateTime->format('A') == "AM")? __('AM') : __('PM')) . '</span>
                            ';
                        } else { $result = ""; }
                        return $result;
                    })
                    ->addColumn('updated_by_at', function ($row) {
                        if ($row->updated_by != null) {
                            $user = User::find($row->updated_by)->name;
                            $dateTime = $row->updated_at;
                            $result = '
                                <span class="text-info">' . $user . '</span><br>
                                <span>' . $dateTime->format('d-m-Y') . '</span><br>
                                <span>' . $dateTime->format('h:i') . '</span>
                                <span>' . (($dateTime->format('A') == "AM")? __('AM') : __('PM')) . '</span>
                            ';
                        } else { $result = ""; }
                        return $result;
                    })
                    ->addColumn('action', function($row){
                        $VoucherButton = auth()->user()->can('print_receipt_vouchers') ?
                            '<div class="menu-item px-2">
                                <a data-id="' . $row["case_id"] . '" class="menu-link px-3" id="print_receipt">' . __("Print receipt") . '</a>
                            </div>' : "";

                        $EditButton = auth()->user()->can('update_receipt_vouchers') ?
                            '<div class="menu-item px-2">
                                <a data-id="' . $row["id"] . '" class="menu-link px-3 edit_btn">' . __("Edit") . '</a>
                            </div>' : "";

                        $ButtonDownload = auth()->user()->can('download attachment_receipt_vouchers') ? ($row["file"] != null ?
                            '<div class="menu-item px-2">
                                <a href="' . route("cases.receipt_vouchers.download", [$row["id"]]) . '" class="menu-link px-3">' . __("Download attachment") . '</a>
                            </div>' : "") : "";
                        return'
                            <div class="btn-group dropstart">
                                <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical fs-3"></i>
                                </a>
                                <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-2" data-kt-menu="true">
                                    '. $EditButton . $VoucherButton . $ButtonDownload .'
                                </ul>
                            </div> ';
                    })
                    ->rawColumns(['checkbox', 'id', 'physician', 'case_number', 'status', 'created_by_at', 'updated_by_at', 'action'])
                    ->make(true);

            } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
        }

        return view("cases.receipt_voucher.index", [
            "users" => User::get(),
        ]);
    }
}
