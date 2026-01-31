<?php

namespace App\Http\Traits\TablesBuilderTraits\Settings\DataSettings;

use App\Models\Branch;
use App\Models\Services;
use App\Models\ServicesCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

trait ServicesTable
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = Services::with("categories", "branches")->select('*');
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->filter(function ($instance) use ($request) {
                        $search = $request->input('search');
                        $category = $request->input('category');
                        if ($request->has('search') && !empty($search)) {
                            $instance ->where('name_ar', 'LIKE', '%' . $search . '%')
                            ->orwhere('name_en', 'LIKE', '%' . $search . '%')
                            ->orwhereHas('categories', function ($query) use ($request) {
                                $query->where('name_ar', 'LIKE', "%{$request ->input('search')}%")
                                    ->orwhere('name_en', 'LIKE', "%{$request ->input('search')}%");
                            });
                        }
                        if (!(!$request->has('category') || empty($category))) {
                            $instance ->where('category', $category);
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
                    ->addColumn('service', function($row) {
                        return app()->getLocale() === "ar" ? $row["name_ar"] : $row["name_en"];
                    })
                    ->addColumn('category', function($row) {
                        return app()->getLocale() === "ar" ? $row->categories->name_ar : $row->categories->name_en;
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
                        $EditButton =
                            auth()->user()->can('update_services') ? '<div class="menu-item px-2">
                               <a data-id="' . $row["id"] . '" class="menu-link px-3 edit_btn">' . __("Edit") . '</a>
                            </div>' : "";

                        $DeleteButton =
                            auth()->user()->can('remove_services') ? '<div class="menu-item px-2">
                                    <a class="menu-link delete-confirm" data-id="' . $row["id"] . '" data-name="' . $name . '">' . __("Delete") . '</a>
                                </div>' : "";

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
                    ->rawColumns(['action', 'service', 'category', 'checkbox', 'status'])
                    ->make(true);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }

        return view("settings.data_settings.services.index", [
            "categories" => ServicesCategories::select("id", "name_ar", "name_en")->get(),
            "branches" => Branch::select("id", "name_ar", "name_en")->get(),
        ]);

    }
}
