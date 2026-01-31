<?php

namespace App\Http\Traits\TablesBuilderTraits\Cases\Orders;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

trait OrdersTable
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $User = auth()->user();
                if ($User->hasRole('physician')) {
                    $data = ((int)$User["receiving_orders"] == 1) ?
                        Order::with("AcceptedBy", "RejectedBy", "CreatedBy", "UpdatedBy", "Designer")->select("*") :
                        Order::with("AcceptedBy", "RejectedBy", "CreatedBy", "UpdatedBy", "Designer")
                                ->where("created_by", Auth::id())->select("*");
                } elseif ($User->hasRole('supervising')) {
                    $Physicians = User::where("supervising", $User["id"]) ->get("id");
                    $data = Order::with("AcceptedBy", "RejectedBy", "CreatedBy", "UpdatedBy", "Designer")
                        ->whereIn("created_by", $Physicians)
                        ->select("*");
                } elseif ($User->hasRole('designer')) {
                    $data = Order::with("AcceptedBy", "RejectedBy", "CreatedBy", "UpdatedBy", "Designer") ->where("designer", Auth::id()) ->select("*");
                } elseif ($User->hasRole(['owner','admin'])) {
                    $data = Order::with("AcceptedBy", "RejectedBy", "CreatedBy", "UpdatedBy", "Designer") ->select("*");
                } elseif ((int)$User["receiving_orders"] == 1) {
                    $data = Order::with("AcceptedBy", "RejectedBy", "CreatedBy", "UpdatedBy", "Designer") ->select("*");
                } else {
                    $data = [];
                }

                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->filter(function ($instance) use ($request) {
                        $search = $request->input('search');
                        if ($request->input('search')) {
                            $instance->where('id', 'LIKE', "%" . $search . "%")
                                ->orwhere('patient_name', 'LIKE', "%" . $search . "%")
                                ->orwhereHas('Physician', function ($query) use ($request) {
                                    $query->where('name', 'LIKE', "%{$request ->input('search')}");
                                });
                        }
                        if ($request->input('status')) {
                            $instance->where('status', $request->input('status'));
                        }
                        if ($request->input('physician')) {
                            $instance->where('created_by', $request->input('physician'));
                        }
                    })
                    ->addColumn('checkbox', function($row) {
                        return '<div class="form-check form-switch d-flex justify-content-center align-items-center">
                                    <input class="form-check-input h-20px w-20px p-0 child_check" data-id="'.$row["id"].'" type="checkbox" data-kt-check="true" />
                                </div>';
                    })
                    ->addColumn('physician', function($row) {
                        return User::where("id", $row ->created_by) ->value("name");
                    })
                    ->addColumn('designer', function($row) {
                        return ($row["designer"] != null)? $row ->Designer ->name : "" ;
                    })
                    ->addColumn('status', function($row) {
                        return match ((int)$row["status"]) {
                            1 => '<span class="badge badge-light-info me-2">' . __("Pending review") . '</span>',
                            2 => '<span class="badge badge-light-success me-2">' . __("Sent to designer") . '</span>',
                            3 => '<span class="badge badge-light-primary me-2">' . __("Approved") . '</span>',
                            default => '<span class="badge badge-light-danger me-2">' . __("Rejected") . '</span>',
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

                        $AttachmentsButton = (auth()->user()->can('view_orders')) ?
                            '<div class="menu-item px-2">
                                <a href="' . route("cases.orders.view", [$row->id]) . '" class="menu-link px-3">' . __("View") . '</a>
                            </div>' : "";

                        // Begin - Accept Button
                        $AcceptButton = ((int)$User["receiving_orders"] == 1 and auth()->user()->can('accept_orders')) ?
                            (((int)$row->status == 1) ?
                                '<div class="menu-item px-2">
                                    <a data-id="' . $row->id . '" class="menu-link px-3 text-primary accept_button">' . __("Accept") . '</a>
                                </div>' : "") : "";
                        // End - Accept Button

                        // Begin - Approve Button
                        $ApproveButton = auth()->user()->can('approve_orders') ? (((int)$row->status == 2 and Auth::id() == $row["designer"]) ?
                            '<div class="menu-item px-2">
                                <a href="'.route("cases.orders.create_case", [$row->id]).'" data-id="' . $row->id . '" class="menu-link px-3 text-primary approve_button">' . __("Approve") . '</a>
                            </div>' : "") : "";
                        // End - Approve Button

                        // Begin - Reject Button
                        $RejectButton = ((int)$User["receiving_orders"] == 1 and auth()->user()->can('reject_orders')) ?
                            (in_array($row->status, [1, 2]) ?
                                '<div class="menu-item px-2">
                                    <a data-id="' . $row->id . '" class="menu-link px-3 text-danger reject_button">' . __("Reject") . '</a>
                                 </div>' : "") : "";
                        // End - Reject Button

                        $EditButton = auth()->user()->can('update_orders') ?
                            '<div class="menu-item px-2">
                                <a href="' . route("cases.orders.edit", [$row->id]) . '" class="menu-link px-3">' . __("Edit") . '</a>
                            </div>' : "";

                        $DeleteButton = auth()->user()->can('remove_orders') ?
                            '<div class="menu-item px-2">
                                <a class="menu-link delete-confirm" data-id="' . $row->id . '" data-name="' . $row->case_number . '">' . __("Delete") . '</a>
                            </div>' : "";

                        return'
                            <div class="btn-group dropstart">
                                  <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical fs-3"></i>
                                  </a>
                                  <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-2" data-kt-menu="true">
                                    '. $AttachmentsButton .  $ApproveButton .  $AcceptButton .  $RejectButton .  $EditButton . $DeleteButton .'
                                  </ul>
                            </div> ';
                    })
                    ->rawColumns(['checkbox', 'physician', 'designer', 'status', 'created_by_at', 'updated_by_at', 'action'])
                    ->make(true);

            } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
        }

        return view("cases.orders.index", [
            "users" => User::get(),
        ]);
    }
}
