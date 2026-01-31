<?php

namespace App\Http\Controllers\Settings\DataSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DataSettings\SpecialOffersRequest;
use App\Http\Traits\TablesBuilderTraits\Settings\DataSettings\SpecialOffersTable;
use App\Models\SpecialOffers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SpecialOffersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_special_offers', ['only' => ['index']]);
        $this->middleware('permission:create_special_offers', ['only' => ['create','store']]);
        $this->middleware('permission:update_special_offers', ['only' => ['update','edit']]);
        $this->middleware('permission:remove_special_offers', ['only' => ['destroy', 'destroy_all']]);
    }
    use SpecialOffersTable;

    public function store(SpecialOffersRequest $request)
    {
        $lock = Cache::lock('SpecialOffersLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {

                SpecialOffers::create($request->all());

                return response() ->json(['success' => 'Special offer created successfully']);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit(int $id)
    {
        $data = SpecialOffers::find($id);
        $response = [
            'id' => $data['id'],
            'offer_name' => $data['offer_name'],
            'doctor_id' => $data['doctor_id'],
            'discount' => $data['discount'],
            'start_date' => date("d-m-Y", strtotime($data['start_date'])),
            'end_date' => date("d-m-Y", strtotime($data['end_date'])),
        ];
        return response() ->json($response);
    }

    public function update(SpecialOffersRequest $request)
    {
        try {
            $category = SpecialOffers::find($request["id"]);
            $category->update([
                'offer_name' => $request['offer_name'],
                'doctor_id' => $request['doctor_id'],
                'discount' => $request['discount'],
                'start_date' => date("Y-m-d", strtotime($request['start_date'])),
                'end_date' => date("Y-m-d", strtotime($request['end_date'])),
            ]);

            return response() ->json(['success' => 'Special offer updated successfully']);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id, Request $request)
    {
        try {
            $data = SpecialOffers::find($id);
            $data ->delete();

            return response() ->json(['success' => 'Special offer deleted successfully']);
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
            SpecialOffers::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => 'Selected records deleted successfully']);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
