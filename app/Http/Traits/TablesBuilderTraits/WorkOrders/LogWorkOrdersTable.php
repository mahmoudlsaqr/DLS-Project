<?php

namespace App\Http\Traits\TablesBuilderTraits\WorkOrders;

use App\Models\WorkOrdersLog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Exception;

trait LogWorkOrdersTable
{
    public function search(Request $request, string $number)
    {
        if (!$request->ajax()) {
            return view('landing.work_orders.search');
        }

        try {
            $data = WorkOrdersLog::with(['workOrders', 'phases'])
                ->whereHas('workOrders', fn($query) => $query
                    ->where('work_order_number', $number)
                    ->orWhere('file_number', $number)
                )
                ->latest()->get();

            if (!$data) {
                return response()->json(['error' => 'No records found'], 404);
            }

            return DataTables::of($data)
            ->addIndexColumn()
                ->setRowId(fn($row) => $row->id)
                ->addColumn('work_order_number', fn($row) => $row->workOrders?->work_order_number ?? '')
                ->addColumn('doctor', fn($row) => getLocaleName($row->workOrders?->doctors, "name_ar", "name_en"))
                ->addColumn('patient_name', fn($row) => $row->workOrders?->patient_name ?? '')
                ->addColumn('file_number', fn($row) => $row->workOrders?->file_number ?? '')
                ->addColumn('phase', fn($row) => getLocaleName($row->phases, 'name_ar', 'name_en'))
                ->addColumn('completion_percent', fn($row) => $row->phases?->completion_percent ?? '')
                ->addColumn('date', fn($row) => getDateDMY($row->created_at))
                ->addColumn('time', fn($row) => getTime($row->created_at))
                ->rawColumns(['phase', 'work_order_number', 'patient_name', 'completion_percent', 'doctor', 'file_number', 'date', 'time'])
                ->make(true);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
