<?php

namespace App\Http\Controllers\Settings\RegionalSettings;

use App\Http\Controllers\Controller;
use App\Http\Traits\TablesBuilderTraits\Settings\RegionalSettings\DistrictsTable;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_districts', ['only' => ['index']]);
        $this->middleware('permission:update status_districts', ['only' => ['update_status', 'update_status_all']]);
    }

    use DistrictsTable;

    public function update_status(Request $request, $id)
    {
        try {
            $data = District::find($id);
            $data ->status = $request ->status;
            $data ->save();

            return response() ->json(['success' => __('Status updated successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }

    public function update_status_all(Request $request)
    {
        try {
            $identities = $request ->get('id');
            $status = $request ->get('status');
            District::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json(['success' => __('Selected records status updated successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }

}
