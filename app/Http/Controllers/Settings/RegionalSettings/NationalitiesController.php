<?php

namespace App\Http\Controllers\Settings\RegionalSettings;

use App\Http\Controllers\Controller;
use App\Http\Traits\TablesBuilderTraits\Settings\RegionalSettings\NationalitiesTable;
use App\Models\Nationalities;
use Illuminate\Http\Request;
use function __;
use function response;

class NationalitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_nationalities', ['only' => ['index']]);
        $this->middleware('permission:update status_nationalities', ['only' => ['update_status', 'update_status_all']]);
    }

    use NationalitiesTable;

    public function update_status(Request $request, $id)
    {
        try {
            $data = Nationalities::find($id);
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
            $identities = $request ->get("id");
            $status = $request ->get('status');
            Nationalities::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json(['success' => __('Selected records status updated successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }

}
