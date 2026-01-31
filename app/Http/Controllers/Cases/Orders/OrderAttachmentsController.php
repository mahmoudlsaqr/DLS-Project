<?php

namespace App\Http\Controllers\Cases\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cases\OrderAttachmentsRequest;
use App\Http\Traits\TablesBuilderTraits\Cases\Orders\OrderAttachmentsTable;
use App\Models\OrderAttachment;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OrderAttachmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_orders', ['only' => ['index']]);
        $this->middleware('permission:add_attachments', ['only' => ['store']]);
        $this->middleware('permission:remove_attachments', ['only' => ['destroy']]);
        $this->middleware('permission:download_attachments', ['only' => ['download']]);
    }

    use OrderAttachmentsTable;

    public function store(OrderAttachmentsRequest $request)
    {
        $lock = Cache::lock('OrderAttachmentLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                $OrderID = $request["order_id"];

                $AttachmentsRepeater = $request["attachments_repeater"];
                $seq = 0;
                foreach ($AttachmentsRepeater as $item) {
                    if (isset($item["file"]) && isset($item['type'])) {
                        $File = $item["file"];
                        $Type = $item['type'];
                        $Description = $item['description'];
                        $fileSize = filesize($File);
                        $request->file($File);
                        $new_file_name = time() . "_" . $OrderID . "_" . $seq . "." . $File->getClientOriginalExtension();
                        Storage::disk('uploads')->put("order_attachments/".$OrderID."/".$new_file_name, file_get_contents($File));
                        OrderAttachment::create([ "order_id" => $OrderID, "file" => $new_file_name, "type" => $Type,"description" => $Description,"filesize" => $fileSize]);
                    }
                }

                return response() ->json(['success' => __('Order attachment created successfully')]);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function destroy($id)
    {
        try {
            $data = OrderAttachment::with("Orders")->find($id);
            $OrderID = $data->Orders->id;

            Storage::disk('uploads')->delete($data['file']);
            $filePath = public_path("uploads/order_attachments/{$OrderID}/{$data['file']}");
            if (File::exists($filePath)) {
                File::delete($filePath);
            } else { return response() ->json(['error' => __("Order attachment doesn't exist")]); }
            $data->delete();

            return response() ->json(['success' => __('Order attachment deleted successfully')]);
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451 Cannot delete or update a parent row: a foreign key constraint fails")) {
                return response() ->json(['error' => __("Cannot delete or update a parent row: a foreign key constraint failed")], 422);
            } else {
                return response() ->json(['error' => $e->getMessage()], 500);
            }
        }
    }

    public function download($id)
    {
        $data = OrderAttachment::with("Orders")->find($id);
        $OrderID = $data->Orders->id;
        $Path = "uploads/order_attachments/{$OrderID}/{$data['file']}";

        return response()->download(public_path($Path));
    }
}
