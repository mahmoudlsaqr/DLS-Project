<?php

namespace App\Http\Traits\TablesBuilderTraits\Settings\DataSettings;

use App\Models\Branch;
use App\Models\Nationalities;
use App\Models\Sections;
use App\Models\Technicians;
use App\Models\WorkingPhases;
use App\Models\WorkOrdersStages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

trait TechniciansTable
{
    public function index(Request $request)
    {
        if ($request ->ajax()) {
            try {
            $data = Technicians::with('sections', 'nationalities', 'branches', 'phases')->select('*');
            return DataTables::of($data)
               ->addIndexColumn()
               ->setRowId('{{$id}}')
               ->setRowClass('')
               ->filter(function ($instance) use ($request) {
                   $search = $request->input('search');
                    if ($search) {
                        $instance  ->where('name_ar', 'LIKE', "%$search%")
                            ->orwhere('name_en', 'LIKE', "%$search%")
                            ->orwhere('email', 'LIKE', "%$search%")
                            ->orwhere('mobile', 'LIKE', "%$search%")
                            ->orwhereHas('sections', function ($query) use ($request) {
                                $query->where('name_ar', 'LIKE', "%{$request ->input('search')}%")
                                    ->orwhere('name_en', 'LIKE', "%{$request ->input('search')}%");
                            });
                    }
                    if ($request->input('status') == 'false' || $request->get('status') == 'true') {
                        $instance->where('status', $request->input('status'));
                    }
                    if ($request->input('nationality')) {
                        $instance->where('nationality_id', $request->input('nationality'));
                    }
                    if ($request->input('section')) {
                        $instance->where('section_id', $request->input('section'));
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
                ->addColumn('mobile', function($row) {
                    return $row["country_code"] . $row["mobile"];
                })
               ->addColumn('section', function ($row) {
                   if (empty($row->section_id)) { return ''; }
                    $locale = app() ->getLocale();
                    return $locale != 'ar' ? $row->sections->name_en : $row->sections->name_ar;
                })
               ->addColumn('nationality', function ($row) {
                   if (empty($row->nationality_id)) { return ''; }
                    $locale = app() ->getLocale();
                    return $locale != 'ar' ? $row->nationalities->Nat_En : $row->nationalities->Nat_Ar;
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
                ->addColumn('created_by', function ($row) {
                    return WhoDidItAndWhen($row->created_at, $row->createdBy);
                })
                ->addColumn('updated_by', function ($row) {
                    return WhoDidItAndWhen($row->updated_at, $row->updatedBy);
                })
               ->addColumn('action', function($row) {
                   app()->getLocale() === "ar" ? $name = $row["name_ar"] : $name = $row["name_en"];
                   $EditButton = auth()->user()->can('update_technicians') ? '<div class="menu-item px-2">
                                   <a data-id="' . $row["id"] . '" class="menu-link px-3 edit_btn">' . __("Edit") . '</a>
                                 </div>' : "";

                   $DeleteButton = auth()->user()->can('remove_technicians') ?
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
                ->rawColumns(['checkbox', 'name', 'mobile', 'section', 'nationality', 'created_by', 'updated_by', 'status', 'action'])
                ->make(true);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }

        return view('settings.data_settings.technicians.index', [
            "sections" => Sections::where('status', 'true')->select (['id', 'name_ar', 'name_en'])->get(),
            "nationalities" => Nationalities::where('status', 'true')->select('id', 'Nat_Ar', 'Nat_En')->get(),
            "branches" => Branch::where('status', 'true')->select (['id', 'name_ar', 'name_en'])->get(),
            "phases" => WorkingPhases::where('status', 'true')->select (['id', 'name_ar', 'name_en'])->get(),
        ]);
    }
}
