<?php

namespace App\Http\Controllers\Settings\DataSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DataSettings\ColorCodesRequest;
use App\Http\Traits\TablesBuilderTraits\Settings\DataSettings\ColorCodesTable;
use App\Models\ColorCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ColorCodesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_color_codes', ['only' => ['index']]);
        $this->middleware('permission:create_color_codes', ['only' => ['create','store']]);
        $this->middleware('permission:update_color_codes', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_color_codes', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove_color_codes', ['only' => ['destroy', 'destroy_all']]);
    }
    use ColorCodesTable;

    public function store(ColorCodesRequest $request)
    {
        $lock = Cache::lock('ColorCodesLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                ColorCodes::create($request->all());

                return response() ->json(['success' => 'Color code created successfully']);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit(int $id)
    {
        $data = ColorCodes::find($id);
        return response() ->json($data);
    }

    public function update(ColorCodesRequest $request)
    {
        try {
            $category = ColorCodes::find($request["id"]);
            $category->update($request->all());

            return response() ->json(['success' => 'Color code updated successfully']);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $data = ColorCodes::find($id);
            if (!empty($request->status)) {
                $data ->status = $request ->status;
            }
            $data ->save();

            return response() ->json(['success' => 'Status updated successfully']);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function updateStatusAll(Request $request)
    {
        try {
            $identities = $request ->get('id');
            $status = $request ->get('status');
            ColorCodes::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json('Selected records status updated successfully');
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id, Request $request)
    {
        try {
            $data = ColorCodes::find($id);
            $data ->delete();

            return response() ->json(['success' => 'Color code deleted successfully']);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }

    public function destroyAll(Request $request)
    {
        try {
            $identities = $request ->get("id");
            ColorCodes::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => 'Selected records deleted successfully']);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
