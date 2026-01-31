<?php

namespace App\Http\Traits\TablesBuilderTraits\Cases;

use App\Models\CaseAttachment;
use App\Models\Cases;
use App\Models\CasesNotes;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

trait CaseAttachmentViewTable
{
    public function view(Request $request, $case_id)
    {
        if ($request->ajax()) {
            try {
                $data = CaseAttachment::with("Cases")->where("case_id", $case_id)->select("*");
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId('{{$id}}')
                    ->setRowClass('')
                    ->filter(function ($instance) use ($request) {
                        $search = $request->input('search');
                        if ($request->input('search')) {
                            $instance->where('id', 'LIKE', "%" . $search . "%")
                                     ->orwhere('description', 'LIKE', "%" . $search . "%");
                        }
                    })
                    ->addColumn('id', function($row) {
                        return '<div class="p-6">'.$row["id"].'</div>';
                    })
                    ->addColumn('file', function($row) {
                        return $row ->file;
                    })
                       ->addColumn('description', function($row) {
                        return $row ->description;
                    })
                    ->addColumn('type', function($row) {
                        return match ((int)$row["type"]) {
                            1 => __("Image"),
                            2 => __("Video"),
                            3 => __("Pdf"),
                            default => __("Stl"),
                        };
                    })
                    ->addColumn('action', function($row) {
                        $CaseNumber = $row->Cases->case_number;
                        $Path = "uploads/case_attachments/{$CaseNumber}/{$row['file']}";

                        $ButtonView = auth()->user()->can('view_attachments') ?
                            match ((int)$row['type']) {
                                1 => '<a class="btn btn-sm btn-icon btn-twitter image_view_btn" data-src="' . asset($Path) . '"><i class="ki-outline ki-eye fs-2"> </i></a>',
                                2 => '<a class="btn btn-sm btn-icon btn-twitter" data-bs-toggle="modal" data-bs-target="#modal_video_' . $row['id'] . '_view"><i class="ki-outline ki-eye fs-2"> </i></a>',
                                default => '',
                            } : "";

                        $ButtonDownload = auth()->user()->can('download_attachments') ?
                            '<a href="' . route("cases.attachments.download", [$row["id"]]) . '" class="btn btn-sm btn-icon btn-twitter">
                                <i class="ki-outline ki-cloud-download fs-2"> </i>
                            </a>' : "";

                        $DeleteButton = auth()->user()->can('remove_attachments') ?
                            '<a data-id="' . $row["id"] . '" data-name="' . $row->file . '" class="btn btn-sm btn-icon btn-danger delete-confirm">
                                <i class="ki-outline ki-trash fs-2"> </i>
                            </a>' : "";

                        return '<div class="d-flex justify-content-start align-items-center gap-2">'.$ButtonView . $ButtonDownload . $DeleteButton.'</div>';
                    })
                    ->rawColumns(['checkbox', 'file', 'type', 'id', 'action'])
                    ->make(true);

            } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
        }

        return view("cases.case_attachments.index", [
            "CaseAttachment" => CaseAttachment::with("Cases")->where("case_id", $case_id)->where("type", 2)->get(),
            "Case" => Cases::with("Physician")->find($case_id),
            "case_id" => $case_id,
            "CaseNotes" => CasesNotes::with("Cases", "User")->where("case_id", $case_id)->orderBy("id", "desc")->get(),
        ]);
    }
}
