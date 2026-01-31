<?php

namespace App\Http\Traits\TablesBuilderTraits\Settings\DataSettings;

use App\Models\Branch;
use App\Models\Clients;
use App\Models\Doctors;
use App\Models\DoctorsServicesDetails;
use App\Models\Nationalities;
use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

trait DoctorsServicesDetailsTable
{
    public function index(Request $request, string $id)
    {
        if ($request ->ajax()) {
            try {
            $data = DoctorsServicesDetails::with('doctors', 'services', 'servicesCategories')->where("doctor_id", $id)->select('*');
            return DataTables::of($data)
               ->addIndexColumn()
               ->setRowId('{{$id}}')
               ->setRowClass('')
               ->filter(function ($instance) use ($request) {
                   $search = $request->input('search');
                    if ($search) {
                        $instance  ->where('service_code', 'LIKE', "{$search}%")
                            ->orwhere('service_price', 'LIKE', "{$search}%")
                            ->orwhere('service_discount', 'LIKE', "{$search}%")
                            ->orwhereHas('doctors', function ($query) use ($request) {
                                $query->where('name_ar', 'LIKE', "%{$request ->input('search')}%")
                                    ->orwhere('name_en', 'LIKE', "%{$request ->input('search')}%");
                            })
                            ->orwhereHas('services', function ($query) use ($request) {
                                $query->where('name_ar', 'LIKE', "%{$request ->input('search')}%")
                                    ->orwhere('name_en', 'LIKE', "%{$request ->input('search')}%");
                            })
                            ->orwhereHas('servicesCategories', function ($query) use ($request) {
                                $query->where('name_ar', 'LIKE', "%{$request ->input('search')}%")
                                    ->orwhere('name_en', 'LIKE', "%{$request ->input('search')}%");
                            });
                    }
                    if ($request->input('status') == 'false' || $request->get('status') == 'true') {
                        $instance->where('status', $request->input('status'));
                    }
                    if ($request->input('service')) {
                        $instance->where('service_id', $request->input('service'));
                    }
                    if ($request->input('category')) {
                        $instance->where('category_id', $request->input('category'));
                    }
                })
                ->addColumn('checkbox', function($row) {
                    return '<div class="form-check form-switch d-flex justify-content-center align-items-center">
                                <input class="form-check-input h-20px w-20px p-0 child_check" data-id="'.$row["id"].'" type="checkbox" data-kt-check="true" />
                            </div>';
                })
               ->addColumn('service', function ($row) {
                   if (empty($row->service_id)) { return ''; }
                    $locale = app() ->getLocale();
                    return $locale != 'ar' ? $row->services->name_en : $row->services->name_ar;
                })
               ->addColumn('category', function ($row) {
                   if (empty($row->category_id)) { return ''; }
                    $locale = app() ->getLocale();
                    return $locale != 'ar' ? $row->servicesCategories->name_en : $row->servicesCategories->name_ar;
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
                   app()->getLocale() === "ar" ? $name = $row->services->name_ar : $name = $row->services->name_en;
                   $DeleteButton = auth()->user()->can('remove details_doctors') ?
                       '<div class="menu-item px-2">
                            <a class="menu-link delete-confirm" data-id="' . $row["id"] . '" data-name="' . $name . '">' . __("Delete") . '</a>
                        </div> ' : "";

                   return '
                        <div class="btn-group dropstart">
                              <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical fs-3"></i>
                              </a>
                              <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-2" data-kt-menu="true">
                                '. $DeleteButton .'
                              </ul>
                        </div>';
                })
                ->rawColumns(['checkbox', 'service', 'category', 'status', 'action'])
                ->make(true);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }

        return view('settings.data_settings.doctors.details.index', [
            "doctor" => Doctors::find($id),
            "id" => $id,
        ]);
    }
}
