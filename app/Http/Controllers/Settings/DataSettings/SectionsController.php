<?php

namespace App\Http\Controllers\Settings\DataSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DataSettings\SectionsRequest;
use App\Http\Traits\TablesBuilderTraits\Settings\DataSettings\SectionsTable;
use App\Models\Sections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SectionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_sections', ['only' => ['index']]);
        $this->middleware('permission:create_sections', ['only' => ['create','store']]);
        $this->middleware('permission:update_sections', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_sections', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove_sections', ['only' => ['destroy', 'destroy_all']]);
    }
    use SectionsTable;

    public function store(SectionsRequest $request)
    {
        $lock = Cache::lock('SectionsLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                Sections::create($request->all());

                return response() ->json(['success' => 'Section created successfully']);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit(int $id)
    {
        $data = Sections::find($id);
        return response() ->json($data);
    }

    public function update(SectionsRequest $request)
    {
        try {
            $category = Sections::find($request["id"]);
            $category->update($request->all());

            return response() ->json(['success' => 'Section updated successfully']);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $data = Sections::find($id);
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
            Sections::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json('Selected records status updated successfully');
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id, Request $request)
    {
        try {
            $data = Sections::find($id);
            $data ->delete();

            return response() ->json(['success' => 'Section deleted successfully']);
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
            Sections::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => 'Selected records deleted successfully']);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
