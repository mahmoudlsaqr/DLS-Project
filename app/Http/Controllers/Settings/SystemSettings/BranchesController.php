<?php

namespace App\Http\Controllers\Settings\SystemSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\SystemSettings\BranchesRequest;
use App\Http\Traits\TablesBuilderTraits\Settings\SystemSettings\BranchesTable;
use App\Models\Branch;
use App\Models\City;
use App\Models\District;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use function __;
use function redirect;
use function view;

class BranchesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_branches', ['only' => ['index']]);
        $this->middleware('permission:create_branches', ['only' => ['create','store']]);
        $this->middleware('permission:update_branches', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_branches', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove_branches', ['only' => ['destroy', 'destroy_all']]);
    }
    use BranchesTable;

    public function create()
    {
        $regions = Region::get();
        $cities = City::get();
        $districts = District::get();

        return view('settings.system_settings.branches.create', compact('regions', 'cities', 'districts'));
    }

    public function store(BranchesRequest $request)
    {
        $lock = Cache::lock('BranchesLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                $branch = Branch::create( $request ->all() );
                $branch ->update([ 'created_by' => Auth::id() ]);

                return response() ->json(['success' => __('Branch created successfully')]);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit(string $id)
    {
        $branch = Branch::find($id);
        $regions = Region::get();
        $cities = City::get();
        $districts = District::get();

        return view('settings.system_settings.branches.edit', compact('branch', 'regions', 'cities', 'districts'));
    }

    public function update(BranchesRequest $request, string $id)
    {
        try {
            $branch = Branch::find($id);
            $branch ->update( $request ->all() );
            $branch ->update([ 'updated_by' => Auth::id() ]);

            return response() ->json(['success' => __('Branch updated successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status(Request $request, $id)
    {
        try {
            $data = Branch::find($id);
            $data ->status = $request ->status;
            $data ->save();

            return response() ->json(['success' => __('Status updated successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status_all(Request $request)
    {
        try {
            $identities = $request ->get('id');
            $status = $request ->get('status');
            Branch::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json(['success' => __('Selected records status updated successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id, Request $request)
    {
        try {
            $data = Branch::find($id);
            $data ->delete();

            return response() ->json(['success' => __('Branch deleted successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }

    public function destroy_all(Request $request)
    {
        try {
            $identities = $request ->get("id");
            Branch::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => __('Selected records deleted successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
