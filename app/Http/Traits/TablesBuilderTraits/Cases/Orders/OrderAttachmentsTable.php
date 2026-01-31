<?php

namespace App\Http\Traits\TablesBuilderTraits\Cases\Orders;

use App\Models\Order;
use App\Models\OrderAttachment;
use App\Models\OrderNote;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

trait OrderAttachmentsTable
{
    public function index(Request $request, $order_id)
    {
        if ($request->ajax()) {
            try {
                $data = OrderAttachment::with("Orders")->where("order_id", $order_id)->select("*");
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
                        return '<div>'.$row["id"].'</div>';
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
                        $OrderID = $row->Orders->id;
                        $Path = "uploads/order_attachments/{$OrderID}/{$row['file']}";

                        $ButtonView = auth()->user()->can('view_attachments') ?
                            match ((int)$row['type']) {
                                1 => '<a class="btn btn-sm btn-icon btn-twitter image_view_btn" data-src="' . asset($Path) . '"><i class="ki-outline ki-eye fs-2"></i></a>',
                                2 => '<a class="btn btn-sm btn-icon btn-twitter" data-bs-toggle="modal" data-bs-target="#modal_video_' . $row['id'] . '_view">
                                        <i class="ki-outline ki-eye fs-2"> </i>
                                      </a>',
                                default => '',
                            } : "";

                        $ButtonDownload = auth()->user()->can('download_attachments') ?
                            '<a href="' . route("cases.orders.attachments.download", [$row["id"]]) . '" class="btn btn-sm btn-icon btn-twitter">
                                <i class="ki-outline ki-cloud-download fs-2"> </i>
                            </a>' : "";

                        $DeleteButton = auth()->user()->can('remove_attachments') ?
                            '<a data-id="' . $row["id"] . '" data-name="' . $row["id"] . '" class="btn btn-sm btn-icon btn-danger delete-confirm">
                                <i class="ki-outline ki-trash fs-2"> </i>
                            </a>' : "";

                        return '<div class="d-flex justify-content-start align-items-center gap-2">'.$ButtonView . $ButtonDownload . $DeleteButton.'</div>';
                    })
                    ->rawColumns(['checkbox', 'file', 'type', 'id', 'action'])
                    ->make(true);

            } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
        }

        return view("cases.order_attachments.index", [
            "Order" => Order::with("AcceptedBy", "CreatedBy", "UpdatedBy")->find($order_id),
            "OrderAttachment" => OrderAttachment::with("Orders")->where("order_id", $order_id)->where("type", 2)->get(),
            "users" => User::where('status', '=', 'true')->get(),
            "order_id" => $order_id,
            "OrderNotes" => OrderNote::with("Orders", "User")->where("order_id", $order_id)->orderBy("id", "desc")->get(),
        ]);
    }
}
