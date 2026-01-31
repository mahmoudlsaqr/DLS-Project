<?php

namespace App\Http\Controllers\Settings\DataSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DataSettings\ServicesCategoriesRequest;
use App\Http\Traits\TablesBuilderTraits\Settings\DataSettings\ServicesCategoriesTable;
use App\Models\ServicesCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServicesCategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_services_categories', ['only' => ['index']]);
        $this->middleware('permission:create_services_categories', ['only' => ['create','store']]);
        $this->middleware('permission:update_services_categories', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_services_categories', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove_services_categories', ['only' => ['destroy', 'destroy_all']]);
    }
    use ServicesCategoriesTable;

    public function store(ServicesCategoriesRequest $request)
    {
        $lock = Cache::lock('ServicesCategoriesLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                ServicesCategories::create($request->all());

                return response() ->json(['success' => 'Service category created successfully']);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit(int $id)
    {
        $data = ServicesCategories::find($id);
        return response() ->json($data);
    }

    public function update(ServicesCategoriesRequest $request)
    {
        try {
            $category = ServicesCategories::find($request["id"]);
            $category->update($request->all());

            return response() ->json(['success' => 'Service category updated successfully']);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $data = ServicesCategories::find($id);
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
            ServicesCategories::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json('Selected records status updated successfully');
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id, Request $request)
    {
        try {
            $data = ServicesCategories::find($id);
            $data ->delete();

            return response() ->json(['success' => 'Service category deleted successfully']);
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
            ServicesCategories::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => 'Selected records deleted successfully']);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
