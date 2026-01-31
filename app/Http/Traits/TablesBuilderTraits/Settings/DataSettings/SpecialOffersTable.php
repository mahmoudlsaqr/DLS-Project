<?php

namespace App\Http\Traits\TablesBuilderTraits\Settings\DataSettings;

use App\Models\Clients;
use App\Models\Doctors;
use App\Models\SpecialOffers;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

trait SpecialOffersTable
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = SpecialOffers::with('doctors')->select('*');
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->filter(function ($instance) use ($request) {
                        $search = $request->input('search');
                        if ($request->has('search') && !empty($search)) {
                            $instance ->where('offer_name', 'LIKE', '%' . $search . '%');
                        }
                    })
                    ->addColumn('checkbox', function($row) {
                        return '<div class="form-check form-switch d-flex justify-content-center align-items-center">
                                    <input class="form-check-input h-20px w-20px p-0 child_check" data-id="'.$row["id"].'" type="checkbox" data-kt-check="true" />
                                </div>';
                    })
                    ->addColumn('doctor', function($row) {
                        return getLocaleName($row->doctors, "name_ar", "name_en");
                    })
                    ->addColumn('start_date', function($row) {
                        return date("d-m-Y", strtotime($row["start_date"]));
                    })
                    ->addColumn('end_date', function($row) {
                        return date("d-m-Y", strtotime($row["end_date"]));
                    })
                    ->addColumn('action', function($row) {
                        $EditButton =
                            auth()->user()->can('update_special_offers') ? '<div class="menu-item px-2">
                               <a data-id="' . $row["id"] . '" class="menu-link px-3 edit_btn">' . __("Edit") . '</a>
                            </div>' : "";

                        $DeleteButton =
                            auth()->user()->can('remove_special_offers') ? '<div class="menu-item px-2">
                                    <a class="menu-link delete-confirm" data-id="' . $row["id"] . '" data-name="' . $row->offer_name . '">' . __("Delete") . '</a>
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
                    ->rawColumns(['action', 'doctor', 'start_date', 'end_date', 'checkbox', 'status'])
                    ->make(true);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }

        return view("settings.data_settings.special_offers.index", [
            'clients' => Clients::where('status', 'true')->select (['id', 'name_ar', 'name_en'])->get(),
            'doctors' => Doctors::where('status', 'true')->select (['id', 'name_ar', 'name_en'])->get(),
        ]);

    }
}
