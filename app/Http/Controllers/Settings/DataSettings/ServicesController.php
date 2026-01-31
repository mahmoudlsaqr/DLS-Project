<?php

namespace App\Http\Controllers\Settings\DataSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DataSettings\ServicesRequest;
use App\Http\Traits\TablesBuilderTraits\Settings\DataSettings\ServicesTable;
use App\Models\DoctorsServicesDetails;
use App\Models\Services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ServicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_services', ['only' => ['index']]);
        $this->middleware('permission:create_services', ['only' => ['create','store']]);
        $this->middleware('permission:update_services', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_services', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove_services', ['only' => ['destroy', 'destroy_all']]);
    }
    use ServicesTable;

    public function store(ServicesRequest $request)
    {
        $lock = Cache::lock('ServicesLock', LOOK_COUNT);
        if (!$lock->get()) {
            return response()->json(['error' => 'Another process is currently executing this operation'], 423);
        }

        try {
            $data = Services::create($request->all());

            $tax = (float)$request["tax"];
            $taxValue = (float)$data["service_price"] * $tax / 100;
            $servicePriceWithTax = (float)$data["service_price"] + $taxValue;
            $data->update([
                'tax_percent' => $tax,
                'tax_value' => $taxValue,
                'service_price_with_tax' => $servicePriceWithTax,
            ]);

            if (!empty($data['branches'])) {
                $branches = $data['branches'];
                $data ->branches() ->sync($branches);
            }

            $doctorsServicesDeatils = DoctorsServicesDetails::select("doctor_id")->distinct()->pluck("doctor_id")->toArray();

            foreach ($doctorsServicesDeatils as $doctor) {
                $doctorsServicesDetailsData = [
                    'doctor_id' => $doctor,
                    'service_id' => $data->id,
                    'category_id' => $data->category_id,
                    'service_code' => $data->service_code,
                    'service_price' => $data->service_price,
                    'price' => $data->service_price,
                ];
                DoctorsServicesDetails::insert($doctorsServicesDetailsData);
            }

            return response()->json(['success' => 'Service created successfully']);
        } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); } finally { $lock->release(); }
    }

    public function edit(int $id)
    {
        $data = Services::find($id);
        return response() ->json($data);
    }

    public function update(ServicesRequest $request)
    {
        try {
            $services = Services::find($request["id"]);
            $services->update($request->all());

            $tax = $request["tax"];
            $taxValue = $services["service_price"] * $tax / 100;
            $servicePriceWithTax = $services["service_price"] + $taxValue;

            $services->update([
                'tax_percent' => (float)$tax,
                'tax_value' => (float)$taxValue,
                'service_price_with_tax' => (float)$servicePriceWithTax,
            ]);

            if (!empty($services['branches'])) {
                $branches = $services['branches'];
                $services ->branches() ->sync($branches);
            }

            return response() ->json(['success' => 'Service updated successfully']);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $data = Services::find($id);
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
            Services::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json('Selected records status updated successfully');
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id, Request $request)
    {
        try {
            $data = Services::find($id);
            $data ->delete();

            return response() ->json(['success' => 'Service deleted successfully']);
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
            Services::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => 'Selected records deleted successfully']);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
