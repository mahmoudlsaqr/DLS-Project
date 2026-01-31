<?php

namespace App\Http\Controllers\Cases;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cases\CaseAttachmentsRequest;
use App\Http\Traits\TablesBuilderTraits\Cases\CaseAttachmentsTable;
use App\Models\CaseAttachment;
use App\Models\Cases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CaseAttachmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_cases', ['only' => ['index']]);
        $this->middleware('permission:add_attachments', ['only' => ['store']]);
        $this->middleware('permission:remove_attachments', ['only' => ['destroy']]);
        $this->middleware('permission:download_attachments', ['only' => ['download']]);
    }

    use CaseAttachmentsTable;

    public function store(CaseAttachmentsRequest $request)
    {
        $lock = Cache::lock('CaseAttachmentLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                $CaseID = $request["case_id"];
                $Case = Cases::find($CaseID);

                $AttachmentsRepeater = $request["attachments_repeater"];
                $seq = 0;
                foreach ($AttachmentsRepeater as $item) {
                    if (isset($item["file"]) && isset($item['type'])) {
                        $File = $item["file"];
                        $Type = $item['type'];
                        $Description = $item['description'];
                        $fileSize = filesize($File);
                        $request->file($File);
                        $new_file_name = time() . "_" . $CaseID . "_" . $seq . "." . $File->getClientOriginalExtension();
                        Storage::disk('uploads')->put("case_attachments/".$Case["case_number"]."/".$new_file_name, file_get_contents($File));
                        CaseAttachment::create([ "case_id" => $CaseID, "file" => $new_file_name, "type" => $Type,"description" => $Description,"filesize" => $fileSize]);
                    }
                }

                return response() ->json(['success' => __('Case attachment created successfully')]);
            } catch (\Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function destroy($id)
    {
        try {
            $data = CaseAttachment::with("Cases")->find($id);
            $CaseNumber = $data->Cases->case_number;

            $filePath = public_path("uploads/case_attachments/{$CaseNumber}/{$data['file']}");
            if (File::exists($filePath)) {
                File::delete($filePath);
            } else { return response() ->json(['error' => __("Case attachment doesn't exist")]); }
            $data->delete();

            return response() ->json(['success' => __('Case attachment deleted successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }

    public function download($id)
    {
        $data = CaseAttachment::with("Cases")->find($id);
        $CaseNumber = $data->Cases->case_number;
        $Path = "uploads/case_attachments/{$CaseNumber}/{$data['file']}";

        return response()->download(public_path($Path));
    }
}
