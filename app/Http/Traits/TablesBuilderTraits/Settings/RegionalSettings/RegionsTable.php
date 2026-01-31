<?php

namespace App\Http\Traits\TablesBuilderTraits\Settings\RegionalSettings;

use App\Models\Region;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

trait RegionsTable
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            try {
                $data = Region::orderBy('id', 'asc')->select("*");
                return DataTables::of($data)
                   ->addIndexColumn()
                   ->setRowId('{{$id}}')
                   ->setRowClass('')
                   ->filter(function ($instance) use ($request) {
                        $search = $request->input('search');
                        if ($request->input('search')) {
                            $instance  ->where('code', 'LIKE', "%" . $search . "%")
                                       ->orwhere('name_ar', 'LIKE', '%' . $search . '%')
                                       ->orwhere('name_en', 'LIKE', "%" . $search . "%")
                                       ->orwhere('population', 'LIKE', "%" . $search . "%");
                        }
                       if ($request->input('status') == 'false' || $request->get('status') == 'true') {
                           $instance->where('status', $request->input('status'));
                       }
                    })
                    ->addColumn('id', function($row) {
                        return '<div>'.$row["id"].'</div>';
                    })
                   ->addColumn('region', function ($row) {
                        $locale = app()->getLocale();
                        ($locale == 'ar')? $name = $row->name_ar : $name = $row->name_en;
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
                   ->rawColumns(['id', 'region', 'status'])
                   ->make(true);

            } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
        }

        return view('settings.regional_settings.regions.index', [ 'Regions' => Region::get() ]);
    }
}
