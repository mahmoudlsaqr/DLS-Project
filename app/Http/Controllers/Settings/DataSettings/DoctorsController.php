<?php

namespace App\Http\Controllers\Settings\DataSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DataSettings\DoctorsRequest;
use App\Http\Traits\TablesBuilderTraits\Settings\DataSettings\DoctorsTable;
use App\Models\Doctors;
use App\Models\DoctorsServicesDetails;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DoctorsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_doctors', ['only' => ['index']]);
        $this->middleware('permission:create_doctors', ['only' => ['create','store']]);
        $this->middleware('permission:update_doctors', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_doctors', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove_doctors', ['only' => ['destroy', 'destroy_all']]);
    }
    use DoctorsTable;

    public function store(DoctorsRequest $request)
    {
        $lock = Cache::lock('DoctorsLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                $data = Doctors::create( $request ->all() );
                $data ->update([ 'created_by' => Auth::id() ]);

                $dataServices = DB::table('services')->get();
                foreach ($dataServices as $row) {
                    $rowArray = [];
                    $rowArray["doctor_id"] = $data["id"];
                    $rowArray["service_id"] = $row->id;
                    $rowArray["category_id"] = $row->category_id;
                    $rowArray["service_code"] = $row->service_code;
                    $rowArray["service_price"] = $row->service_price;
                    $rowArray["price"] = $row->service_price;
                    DoctorsServicesDetails::insert($rowArray);
                }

                return response() ->json(['success' => __('Doctor created successfully'), 'id' => $data["id"]]);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit(int $id)
    {
        $data = Doctors::find($id);
        return response() ->json($data);
    }

    public function update(DoctorsRequest $request, string $id)
    {
        try {
            $data = Doctors::find($id);
            $data ->update( $request ->all() );
            $data ->update([ 'updated_by' => Auth::id() ]);

            return response() ->json(['success' => __('Doctor updated successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status(Request $request, $id)
    {
        try {
            $data = Doctors::find($id);
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
            Doctors::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json(['success' => __('Selected records status updated successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id, Request $request)
    {
        try {
            $data = Doctors::find($id);
            $data ->delete();

            return response() ->json(['success' => __('Doctor deleted successfully')]);
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
            Doctors::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => __('Selected records deleted successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
