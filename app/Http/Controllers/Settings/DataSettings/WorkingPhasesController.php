<?php

namespace App\Http\Controllers\Settings\DataSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DataSettings\WorkingPhasesRequest;
use App\Http\Traits\TablesBuilderTraits\Settings\DataSettings\WorkingPhasesTable;
use App\Models\WorkingPhases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WorkingPhasesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_working_phases', ['only' => ['index']]);
        $this->middleware('permission:create_working_phases', ['only' => ['create','store']]);
        $this->middleware('permission:update_working_phases', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_working_phases', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove_working_phases', ['only' => ['destroy', 'destroy_all']]);
    }
    use WorkingPhasesTable;

    public function store(WorkingPhasesRequest $request)
    {
        $lock = Cache::lock('WorkingPhasesLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                WorkingPhases::create($request->all());

                return response() ->json(['success' => 'Working phase created successfully']);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit(int $id)
    {
        $data = WorkingPhases::find($id);
        return response() ->json($data);
    }

    public function update(WorkingPhasesRequest $request)
    {
        try {
            $category = WorkingPhases::find($request["id"]);
            $category->update($request->all());

            return response() ->json(['success' => 'Working phase updated successfully']);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $data = WorkingPhases::find($id);
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
            WorkingPhases::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json('Selected records status updated successfully');
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id, Request $request)
    {
        try {
            $data = WorkingPhases::find($id);
            $data ->delete();

            return response() ->json(['success' => 'Working phase deleted successfully']);
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
            WorkingPhases::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => 'Selected records deleted successfully']);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
