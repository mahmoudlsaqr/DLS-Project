<?php

namespace App\Http\Controllers\Settings\DataSettings;

use App\Http\Controllers\Controller;
use App\Http\Traits\TablesBuilderTraits\Settings\DataSettings\DoctorsServicesDetailsTable;
use App\Models\Doctors;
use App\Models\DoctorsServicesDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DoctorsServicesDetailsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view details_doctors', ['only' => ['index']]);
        $this->middleware('permission:update details_doctors', ['only' => ['update','edit']]);
        $this->middleware('permission:update status details_doctors', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove details_doctors', ['only' => ['destroy', 'destroy_all']]);
        $this->middleware('permission:sync services_details_doctors', ['only' => ['syncServices']]);
        $this->middleware('permission:reset services_details_doctors', ['only' => ['resetServices']]);
    }
    use DoctorsServicesDetailsTable;

    public function update(Request $request, string $id)
    {
        $lock = Cache::lock('DoctorsServicesDetailsLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {

                // Validate request data
                $validated = $request->validate([
                    'service_search' => 'required|integer|in:1,2,3',
                    'service_price' => 'nullable|numeric',
                    'service_discount' => 'nullable|numeric|max:100|min:1',
                    'service_category' => 'nullable|integer',
                    'service' => 'nullable|integer',
                ]);

                $query = DoctorsServicesDetails::where("doctor_id", $id);

                switch ((int)$validated['service_search']) {
                    case 1:
                        $dataContract = $query->get();
                        if ($dataContract->isEmpty()) {
                            return response()->json(['error' => 'Table is empty'], 404);
                        }
                        break;

                    case 2:
                        if (!empty($validated['service_category'])) {
                            $dataContract = $query->where("category_id", $validated['service_category'])->get();
                        } else {
                            return response()->json(['error' => 'Category required'], 404);
                        }
                        if (!empty($dataContract)) {
                            if ($dataContract->isEmpty()) {
                                return response()->json(['error' => 'Category not found'], 404);
                            }
                        }
                        break;

                    case 3:
                        if (!empty($validated['service'])) {
                            $dataContract = $query->where("service_id", $validated['service'])->get();
                        } else {
                            return response()->json(['error' => 'Service required'], 404);
                        }
                        if (is_null($dataContract)) {
                            return response()->json(['error' => 'Service not found'], 404);
                        }
                        break;
                }

                // Update records
                if (!empty($dataContract)) {
                    foreach ($dataContract as $row) {
                        if (!empty($validated['service_discount'])) {
                            $discount = $validated['service_discount'];
                            $servicePrice = $validated['service_price'] ?? $row['service_price'];
                            $discountVal = $discount * $servicePrice / 100;
                            $price = $servicePrice - $discountVal;
                        } else {
                            if ((int)$row['service_discount'] === 0) {
                                $price = $validated['service_price'];
                            } else {
                                $price = $row['price'];
                            }
                        }

                        $row->update([
                            'service_discount' => $validated['service_discount'] ?? $row['service_discount'],
                            'service_price' => $validated['service_price'] ?? $row['service_price'],
                            'price' => $price,
                        ]);
                    }
                }

                return response()->json(['success' => 'Data updated successfully'], 200);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function update_status(Request $request, $id)
    {
        try {
            $data = DoctorsServicesDetails::find($id);
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
            DoctorsServicesDetails::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json(['success' => __('Selected records status updated successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy(int $id)
    {
        try {
            $data = DoctorsServicesDetails::find($id);
            $data ->delete();

            return response() ->json(['success' => __('Doctor service deleted successfully')]);
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
            DoctorsServicesDetails::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => __('Selected records deleted successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }

    public function syncServices(int $id)
    {
        try {
            $doctor = Doctors::findOrFail($id);
            $dataServices = DB::table('services')->get();

            foreach ($dataServices as $row) {
                $exists = DoctorsServicesDetails::where('doctor_id', $doctor["id"])
                    ->where('service_id', $row->id)
                    ->exists();

                if (!$exists) {
                    $rowArray = [
                        "doctor_id" => $doctor["id"],
                        "service_id" => $row->id,
                        "category_id" => $row->category_id,
                        "service_code" => $row->service_code,
                        "service_price" => $row->service_price,
                        "price" => $row->service_price,
                    ];
                    DoctorsServicesDetails::insert($rowArray);
                }
            }

            return response() ->json(['success' => __('Sync completed successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function resetServices(int $id)
    {
        try {
            $doctor = Doctors::findOrFail($id);
            $dataServices = DB::table('services')->get();

            $oldData = DoctorsServicesDetails::where('doctor_id', $doctor["id"]) ->get();
            foreach ($oldData as $row) {
                $row->delete();
            }

            foreach ($dataServices as $row) {
                $rowArray = [
                    "doctor_id" => $doctor["id"],
                    "service_id" => $row->id,
                    "category_id" => $row->category_id,
                    "service_code" => $row->service_code,
                    "service_price" => $row->service_price,
                    "price" => $row->service_price,
                ];
                DoctorsServicesDetails::insert($rowArray);
            }

            return response() ->json(['success' => __('Reset services successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }
}
