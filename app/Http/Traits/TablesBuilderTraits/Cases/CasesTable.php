<?php

namespace App\Http\Traits\TablesBuilderTraits\Cases;

use App\Models\Cases;
use App\Models\ReceiptVoucher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

trait CasesTable
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $User = auth()->user();
                if ($User->hasRole('physician')) {
                    $data = Cases::with("Physician", "Supervising")
                        ->where("physician", Auth::id())
                        ->where(function($query) {
                            $query->where("status", 2) ->orWhere("status", 3) ->orWhere("status", 5);
                        })
                        ->select("*");
                } elseif ($User->hasRole('supervising')) {
                    $data = Cases::with("Physician", "Supervising") ->where("supervising", Auth::id()) ->select("*");
                } elseif ($User->hasRole('designer')) {
                    $data = Cases::with("Physician", "Supervising") ->where("created_by", Auth::id()) ->select("*");
                } else {
                    $data = Cases::with("Physician", "Supervising") ->select("*");
                }

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->filter(function ($instance) use ($request) {
                        $search = $request->input('search');
                        if ($request->input('search')) {
                            $instance->where('case_number', 'LIKE', "%" . $search . "%")
                                ->orwhere('patient_name', 'LIKE', "%" . $search . "%")
                                ->orwhereHas('Physician', function ($query) use ($request) {
                                    $query->where('name', 'LIKE', "%{$request ->input('search')}");
                                })
                                ->orwhereHas('Supervising', function ($query) use ($request) {
                                    $query->where('name', 'LIKE', "%{$request ->input('search')}");
                                });
                        }
                        if ($request->input('status')) {
                            $instance->where('status', $request->input('status'));
                        }
                        if ($request->input('physician')) {
                            $instance->where('physician', $request->input('physician'));
                        }
                    })
                    ->addColumn('checkbox', function($row) {
                        return '<div class="form-check form-switch d-flex justify-content-center align-items-center">
                                    <input class="form-check-input h-20px w-20px p-0 child_check" data-id="'.$row["id"].'" type="checkbox" data-kt-check="true" />
                                </div>';
                    })
                    ->addColumn('physician', function($row) {
                        return ($row["physician"] != null)? $row ->Physician ->name : "" ;
                    })
                    ->addColumn('supervising', function($row) {
                        return ($row["supervising"] != null)? $row ->Supervising ->name : "" ;
                    })
                    ->addColumn('status', function($row) {
                        return match ((int)$row["status"]) {
                            1 => '<span class="badge badge-light-info me-2">' . __("Waiting for supervising") . '</span>',
                            2 => '<span class="badge badge-light-success me-2">' . __("Waiting for physician") . '</span>',
                            3 => '<span class="badge badge-light-primary me-2">' . __("Approved") . '</span>',
                            4 => '<span class="badge badge-light-danger me-2">' . __("Rejected from supervision") . '</span>',
                            default => '<span class="badge badge-light-danger me-2">' . __("Rejected from physician") . '</span>',
                        };
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
                        $User = Auth::user();

                        $AttachmentsButton = (auth()->user()->can('view_cases')) ?
                            '<div class="menu-item px-2">
                                <a href="' . route("cases.cases_list.view", [$row->id]) . '" class="menu-link px-3">' . __("View") . '</a>
                             </div>' : "";

                        // Begin - Accept Button
                        $AcceptButton = auth()->user()->can('accept_cases') ? ((((int)$row->status === 1 and $User->hasRole('supervising'))
                            or ((int)$row->status === 2 and $User->hasRole('physician'))) ?
                            '<div class="menu-item px-2">
                                <a data-id="' . $row->id . '" data-name="' . $row->case_number . '" class="menu-link px-3 text-primary accept_button">' . __("Accept") . '</a>
                            </div>' : "") : "";
                        // End - Accept Button

                        // Begin - Reject Button
                        if (auth()->user()->can('reject_cases')) {
                            if ($User->hasRole('supervising')) {
                                $RejectButton = in_array((int)$row->status, [2, 3, 4, 5]) ? "" :
                                '<div class="menu-item px-2">
                                    <a data-id="' . $row->id . '" class="menu-link px-3 text-danger reject_button">' . __("Reject") . '</a>
                                 </div>';
                            } elseif ($User->hasRole('physician')) {
                                $RejectButton = in_array((int)$row->status, [1, 3, 4, 5]) ? "" :
                                '<div class="menu-item px-2">
                                    <a data-id="' . $row->id . '" class="menu-link px-3 text-danger reject_button">' . __("Reject") . '</a>
                                 </div>';
                            } else { $RejectButton = ""; }
                        } else { $RejectButton = ""; }
                        // End - Reject Button

                        $Voucher = ReceiptVoucher::where("case_id", $row->id)->first();
                        if ((int)$row->status == 3 and !$Voucher) {
                            $VoucherButton = auth()->user()->can('create_receipt_vouchers') ?
                                '<div class="menu-item px-2">
                                    <a data-id="' . $row->id . '" class="menu-link px-3" id="receipt_voucher">' . __("Receipt voucher") . '</a>
                                </div>' : "";
                        } elseif ((int)$row->status == 3 and $Voucher) {
                            $VoucherButton = auth()->user()->can('print_receipt_vouchers') ?
                                '<div class="menu-item px-2">
                                    <a data-id="' . $row->id . '" class="menu-link px-3" id="print_receipt">' . __("Print receipt") . '</a>
                                </div>' : "";
                        } else { $VoucherButton = ""; }

                        $EditButton = auth()->user()->can('update_cases') ?
                            '<div class="menu-item px-2">
                                <a href="' . route("cases.cases_list.edit", [$row->id]) . '" class="menu-link px-3">' . __("Edit") . '</a>
                            </div>' : "";

                        $DeleteButton = auth()->user()->can('remove_cases') ?
                            '<div class="menu-item px-2">
                                <a class="menu-link delete-confirm" data-id="' . $row->id . '" data-name="' . $row->case_number . '">' . __("Delete") . '</a>
                            </div>' : "";

                        return'
                            <div class="btn-group dropstart">
                                  <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical fs-3"></i>
                                  </a>
                                  <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-2" data-kt-menu="true">
                                    '. $AttachmentsButton .  $AcceptButton .  $RejectButton .  $VoucherButton .  $EditButton . $DeleteButton .'
                                  </ul>
                            </div> ';
                    })
                    ->rawColumns(['checkbox', 'physician', 'status', 'case_note', 'created_by_at', 'updated_by_at', 'action'])
                    ->make(true);

            } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
        }

        return view("cases.cases_list.index", [
            "users" => User::get(),
        ]);
    }
}
