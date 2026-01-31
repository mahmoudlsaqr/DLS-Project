<?php

namespace App\Http\Traits\TablesBuilderTraits\Settings\SystemSettings;

use App\Models\Branch;
use App\Models\City;
use App\Models\District;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

trait BranchesTable
{
    public function index(Request $request)
    {
        if ($request ->ajax()) {
            try {
            $data = Branch::with('Region', 'City')->select('*');
            return DataTables::of($data)
               ->addIndexColumn()
               ->setRowId('{{$id}}')
               ->setRowClass('')
               ->filter(function ($instance) use ($request) {
                   $search = $request->input('search');
                    if ($search) {
                        $instance  ->where('name_ar', 'LIKE', "%$search%")
                           ->orwhere('name_en', 'LIKE', "%$search%")
                           ->orwhere('code', 'LIKE', "%$search%")
                           ->orwhere('tin', 'LIKE', "%$search%")
                           ->orwhere('com_reg', 'LIKE', "%$search%")
                           ->orwhere('address', 'LIKE', "%$search%")
                           ->orwhere('phone', 'LIKE', "%$search%")
                           ->orwhere('mobile', 'LIKE', "%$search%")
                           ->orwhere('longitude', 'LIKE', "%$search%")
                           ->orwhere('latitude', 'LIKE', "%$search%");
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
               ->addColumn('name', function ($row) {
                    $locale = app() ->getLocale();
                    return $locale != 'ar' ? $row->name_en : $row->name_ar;
                })
               ->addColumn('region', function ($row) {
                    $locale = app() ->getLocale();
                    return $locale != 'ar' ? $row->Region->name_en : $row->Region->name_ar;
                })
               ->addColumn('city', function ($row) {
                    $locale = app() ->getLocale();
                    return $locale != 'ar' ? $row->City->name_en : $row->City->name_ar;
                })
               ->addColumn('district', function ($row) {
                    $locale = app() ->getLocale();
                    return $locale != 'ar' ? $row->District->name_en : $row->District->name_ar;
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
                   app()->getLocale() === "ar" ? $name = $row["name_ar"] : $name = $row["name_en"];
                   $EditButton = auth()->user()->can('update_branches') ?
                       '<div class="menu-item px-2">
                            <a href="' . route("settings.system_settings.branches.edit", [$row["id"]]) . '" class="menu-link px-3">' . __("Edit") . '</a>
                        </div> ' : "";

                   $DeleteButton = auth()->user()->can('remove_branches') ?
                       '<div class="menu-item px-2">
                            <a class="menu-link delete-confirm" data-id="' . $row["id"] . '" data-name="' . $name . '">' . __("Delete") . '</a>
                        </div> ' : "";

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
                ->rawColumns(['checkbox', 'name', 'region', 'city', 'district', 'status', 'action'])
                ->make(true);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }

        return view('settings.system_settings.branches.index', [
            "branches" => Branch::get(),
            "regions" => Region::get(),
            "cities" => City::get(),
            "districts" => District::get(),
            ]);
    }
}
