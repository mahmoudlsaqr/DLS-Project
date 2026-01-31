<?php

namespace App\Http\Traits\TablesBuilderTraits\Settings\RegionalSettings;

use App\Models\Nationalities;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

trait NationalitiesTable
{
    public function index(Request $request)
    {
        if ($request ->ajax()) {
            try {
                $data = Nationalities::select("*");
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->filter(function ($instance) use ($request) {
                        $search = $request ->input('search');
                        if ($request ->input('search')) {
                            $instance ->where('Numeric_3', 'LIKE', "%" . $search . "%")
                            ->orwhere('Alpha_2', 'LIKE', "%" . $search . "%")
                            ->orwhere('Alpha_3', 'LIKE', "%" . $search . "%")
                            ->orwhere('Nat_Ar', 'LIKE', "%" . $search . "%")
                            ->orwhere('Nat_En', 'LIKE', "%" . $search . "%")
                            ->orwhere('Country_Ar', 'LIKE', "%" . $search . "%")
                            ->orwhere('Country_EN', 'LIKE', "%" . $search . "%");
                        }
                        if ($request->input('status') == 'false' || $request->get('status') == 'true') {
                            $instance->where('status', $request->input('status'));
                        }
                    })
                    ->addColumn('id', function($row) {
                        return '<div>'.$row["id"].'</div>';
                    })
                    ->addColumn('nationality', function ($row) {
                        $url= asset('uploads/flags/'.$row ->Numeric_3.'.svg');
                        $locale = app() ->getLocale();
                        $locale != 'ar' ? $name = $row ->Nat_En : $name = $row ->Nat_Ar;
                        return '
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-45px overflow-hidden me-3">
                                    <div class="symbol-label">
                                        <img src='.$url.' alt="'.$row ->Numeric_3.'" class="w-100">
                                    </div>
                                </div>
                                <div class="d-flex flex-column"><span class="text-gray-800 mb-1">'.$name.'</span></div>
                            </div>';
                    })
                    ->addColumn('country', function ($row) {
                        $locale = app() ->getLocale();
                        $locale != 'ar' ? $name = $row ->Country_EN : $name = $row ->Country_Ar;
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
                    ->rawColumns(['id', 'nationality', 'country', 'status', 'flag'])
                    ->make(true);

            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
        }

        return view('settings.regional_settings.nationalities.index', [ 'Nationalities' => Nationalities::get() ]);
    }
}
