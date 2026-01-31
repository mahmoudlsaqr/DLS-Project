<?php

namespace App\Http\Traits\TablesBuilderTraits\Settings\RegionalSettings;

use App\Models\City;
use App\Models\District;
use App\Models\Region;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

trait DistrictsTable
{
    public function index(Request $request)
    {
        if ($request ->ajax()) {
            try {
            $data = District::with('Region', 'City') ->select('*');
            return DataTables::of($data)
                ->addIndexColumn()
                ->setRowId('{{$id}}')
                ->setRowClass('')
                ->filter(function ($instance) use ($request) {
                    $search = $request ->input('search');
                    if ($search) {
                        $instance ->where('name_ar', 'LIKE', '%' . $search . '%')
                        ->orwhere('name_en', 'LIKE', '%' . $search . '%');
                    }
                    if ($request ->get('regions')) {
                        $instance ->where('region_id', $request ->get('regions'));
                    }
                    if ($request ->get('cities')) {
                        $instance ->where('city_id', $request ->get('cities'));
                    }
                    if ($request->input('status') == 'false' || $request->get('status') == 'true') {
                        $instance->where('status', $request->input('status'));
                    }
                })
                ->addColumn('id', function($row) {
                    return '<div>'.$row["id"].'</div>';
                })
                ->addColumn('district', function ($row) {
                    $locale = app() ->getLocale();
                    ($locale == 'ar')? $name = $row ->name_ar : $name = $row ->name_en;
                    return $name;
                })
                ->addColumn('city', function ($row) {
                    $locale = app() ->getLocale();
                    ($locale == 'ar')? $name = $row ->City ->name_ar : $name = $row ->City ->name_en;
                    return $name;
                })
                ->addColumn('region', function ($row) {
                    $locale = app() ->getLocale();
                    ($locale == 'ar')? $name = $row ->Region ->name_ar : $name = $row ->Region ->name_en;
                    return $name;
                })
                ->addColumn('status', function($row) {
                    $row->status == 'true' ? $checked = "checked" : $checked = "";
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
                ->rawColumns(['id', 'district', 'city', 'region', 'status'])
                ->make(true);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }

        return view('settings.regional_settings.districts.index', [
            'cities'        => City::orderby('id','asc') ->select('id','name_ar','name_en') ->distinct() ->get(),
            'regions'       => Region::orderby('id','asc') ->select('id','name_ar','name_en') ->distinct() ->get(),
        ]);
    }
}
