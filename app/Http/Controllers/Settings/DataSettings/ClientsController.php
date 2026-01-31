<?php

namespace App\Http\Controllers\Settings\DataSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DataSettings\ClientsRequest;
use App\Http\Traits\TablesBuilderTraits\Settings\DataSettings\ClientsTable;
use App\Models\Branch;
use App\Models\City;
use App\Models\Clients;
use App\Models\District;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ClientsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_clients', ['only' => ['index']]);
        $this->middleware('permission:create_clients', ['only' => ['create','store']]);
        $this->middleware('permission:update_clients', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_clients', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove_clients', ['only' => ['destroy', 'destroy_all']]);
    }
    use ClientsTable;

    public function create()
    {
        $regions = Region::get();
        $cities = City::get();
        $districts = District::get();

        return view('settings.data_settings.clients.create', compact('regions', 'cities', 'districts'));
    }

    public function store(ClientsRequest $request)
    {
        $lock = Cache::lock('ClientsLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                $data = Clients::create( $request ->all() );
                $data ->update([ 'created_by' => Auth::id() ]);

                return response() ->json(['success' => __('Client created successfully')]);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit(string $id)
    {
        $client = Clients::find($id);
        $regions = Region::get();
        $cities = City::get();
        $districts = District::get();

        return view('settings.data_settings.clients.edit', compact('client', 'regions', 'cities', 'districts'), [
            'branches' => Branch::where('status', '=', 'true')->select (['id', 'name_ar', 'name_en'])->get(),
        ]);
    }

    public function update(ClientsRequest $request, string $id)
    {
        try {
            $data = Clients::find($id);
            $data ->update( $request ->all() );
            $data ->update([ 'updated_by' => Auth::id() ]);

            return response() ->json(['success' => __('Client updated successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status(Request $request, $id)
    {
        try {
            $data = Clients::find($id);
            if (!empty($request->status)) {
                $data ->status = $request ->status;
            }
            $data ->save();

            return response() ->json(['success' => __('Status updated successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status_all(Request $request)
    {
        try {
            $identities = $request ->get('id');
            $status = $request ->get('status');
            Clients::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json(['success' => __('Selected records status updated successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id, Request $request)
    {
        try {
            $data = Clients::find($id);
            $data ->delete();

            return response() ->json(['success' => __('Client deleted successfully')]);
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
            Clients::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => __('Selected records deleted successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
