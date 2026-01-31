<?php

namespace App\Http\Controllers\Settings\RegionalSettings;

use App\Http\Controllers\Controller;
use App\Http\Traits\TablesBuilderTraits\Settings\RegionalSettings\RegionsTable;
use App\Models\Region;
use Illuminate\Http\Request;

class RegionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_regions', ['only' => ['index']]);
        $this->middleware('permission:update status_regions', ['only' => ['update_status', 'update_status_all']]);
    }

    use RegionsTable;

    public function update_status(Request $request, $id)
    {
        try {
            $data = Region::find($id);
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
            Region::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json(['success' => __('Selected records status updated successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }

}
