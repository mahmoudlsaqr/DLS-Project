<?php

namespace App\Http\Traits\TablesBuilderTraits\Users;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

trait UsersTable
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = User::select('*');
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->filter(function ($instance) use ($request) {
                            $search = $request->input('search');
                            $roles = $request->input('roles');
                        if ($request->has('search') && !empty($search)) {
                            $instance ->where('name', 'LIKE', '%' . $search . '%')
                            ->orwhere('email', 'LIKE', '%' . $search . '%');
                        }
                        if (!(!$request->has('roles') || empty($roles))) {
                            $instance ->where('role', $roles);
                        }
                        if ($request->input('status') == 'false' || $request->get('status') == 'true') {
                            $instance->where('status', $request->input('status'));
                        }
                    })
                    ->addColumn('checkbox', function($row) {
                        return '<div class="form-check form-switch d-flex justify-content-center align-items-center">
                                    <input class="form-check-input h-20px w-20px p-0 child_check" data-id="'.$row["id"].'" type="checkbox" data-kt-check="true" />
                                </div>';
                    })
                    ->addColumn('user', function($row) {
                        if ($row ->avatar !== null) {
                            $img = '<img src="'.asset("/uploads/avatars/".$row ->avatar).'" alt="'.$row ->avatar.'" class="w-100" />';
                        } else {
                            $img = '<img src="'.asset("/uploads/avatars/blank.png").'" alt="blank" class="w-100" />';
                        }
                        return '
                        <div class="d-flex align-items-center">
                            <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label">'.$img.'</div>
                            </div>
                            <div class="d-flex flex-column justify-content-start align-items-start">
                                <span class="text-gray-800 mb-1">'.$row["name"].'</span>

                                <span class="badge badge-light-success mx-2">'.$row->roles->value("name").'</span>
                            </div>
                        </div> ';
                    })
                    ->addColumn('mobile', function($row) {
                        return $row["country_code"] . $row["mobile"];
                    })
                    ->addColumn('status', function($row) {
                        $row->status != 'true' ? $checked = "" : $checked = "checked";
                        $status = $row->status == 'true' ?
                            '<span class="badge badge-light-primary">' . __('Active') . '</span>' :
                            '<span class="badge badge-light-danger">' . __('Inactive') . '</span>';

                        $Checkbox = '<div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input h-20px w-20px p-0" '. $checked .'
                                        type="checkbox" name="status" value="'.$row["status"].'"
                                        data-id="'.$row["id"].'" id="userStatus'.$row ->id.'"/>
                                     </div>';
                        return '<div class="d-flex justify-content-start align-items-center">'.$Checkbox . $status.'</div>';
                    })
                    ->addColumn('action', function($row) {
                        $EditButton = auth()->user()->can('update_users') ? '<div class="menu-item px-2">
                           <a data-id="' . $row["id"] . '" class="menu-link px-3 edit_btn">' . __("Edit") . '</a>
                        </div>' : "";

                        $DeleteButton = auth()->user()->can('remove_users') ? ($row["id"] !== Auth::id() ? '<div class="menu-item px-2">
                            <a class="menu-link delete-confirm" data-id="' . $row["id"] . '" data-name="' . $row["name"] . '">' . __("Delete") . '</a>
                        </div>' : "") : "";

                        return '
                        <div class="btn-group dropstart">
                            <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical fs-3"></i>
                            </a>
                            <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-2" data-kt-menu="true">
                                '. $EditButton . $DeleteButton .'
                            </ul>
                        </div>';
                    })
                    ->rawColumns(['action', 'user', 'mobile', 'checkbox', 'status'])
                    ->make(true);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }

        return view('users.users_management.index', [
            "roles" => Role::get(),
            "users" => User::get(),
            "branches" => Branch::where('status', 'true')->select (['id', 'name_ar', 'name_en'])->get(),
        ]);
    }
}
