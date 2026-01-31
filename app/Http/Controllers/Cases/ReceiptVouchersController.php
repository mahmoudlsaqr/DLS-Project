<?php

namespace App\Http\Controllers\Cases;

use App\Http\Controllers\Controller;
use App\Http\Traits\TablesBuilderTraits\Cases\ReceiptVouchersTable;
use App\Models\CaseAttachment;
use App\Models\ReceiptVoucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReceiptVouchersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_receipt_vouchers', ['only' => ['index', 'edit', 'update']]);
        $this->middleware('permission:update_receipt_vouchers', ['only' => ['edit', 'update']]);
    }

    use ReceiptVouchersTable;

    public function edit($id)
    {
        try {
            $items = ReceiptVoucher::find($id);
            return response() ->json($items);

        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update(Request $request)
    {
        try {
            $request ->validate([
                'reason_for_rejection' => 'nullable|string',
                'file' => 'nullable|mimes:jpg,jpeg,png,pdf',
            ]);

            $ReceiptID = $request["id"];
            $Receipt = ReceiptVoucher::find($ReceiptID);
            $Receipt ->update($request ->all());

            if ($request->hasFile("file")) {
                Storage::disk('uploads') ->deleteDirectory("case_receipts/".$Receipt->Cases["case_number"]);
                $File = $request["file"];
                $request->file($File);
                $new_file_name = time() . "_" . $Receipt["case_id"] . "." . $File->extension();
                Storage::disk('uploads')->put("case_receipts/".$Receipt->Cases["case_number"]."/".$new_file_name, file_get_contents($File));
                $Receipt->update(["file" => $new_file_name]);
            }

            $Receipt ->update([ "updated_by" => Auth::id() ]);

            return response() ->json(['success' => __('Receipt voucher updated successfully')]);
        } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function download($id)
    {
        $data = ReceiptVoucher::with("Cases")->find($id);
        $CaseNumber = $data->Cases->case_number;
        $Path = "uploads/case_receipts/{$CaseNumber}/{$data['file']}";

        return response()->download(public_path($Path));
    }
}
