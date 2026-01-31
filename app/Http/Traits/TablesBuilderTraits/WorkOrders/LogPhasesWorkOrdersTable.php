<?php

namespace App\Http\Traits\TablesBuilderTraits\WorkOrders;

use App\Models\Doctors;
use App\Models\WorkOrdersLog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

trait LogPhasesWorkOrdersTable
{
    public function searchLogPhases(Request $request, string $number)
    {
        if ($request->ajax()) {
            try {
                $data = WorkOrdersLog::with("workOrders", "phases")
                    ->whereHas('workOrders', fn($query) => $query
                        ->where('work_order_number', $number)
                        ->orwhere('file_number', $number)
                    )
                    ->select("*");
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->setRowId(fn($row) => $row->id)
                    ->addColumn('work_order_number', function ($row) {
                        return $row->workOrders->work_order_number;
                    })
                    ->addColumn('doctor', function ($row) {
                        $doctor = Doctors::find($row->workOrders->doctor_id);
                        return getLocaleName($doctor, "name_ar", "name_en");
                    })
                    ->addColumn('patient_name', function ($row) {
                        return $row->workOrders->patient_name;
                    })
                    ->addColumn('file_number', function ($row) {
                        return $row->workOrders->file_number;
                    })
                    ->addColumn('phase', function ($row) {
                        return getLocaleName($row->phases, "name_ar", "name_en");
                    })
                    ->addColumn('completion_percent', function ($row) {
                        return $row->phases->completion_percent;
                    })
                    ->addColumn('date', function ($row) {
                        if (empty($row->created_at)) { return ''; }
                        return $row ->created_at ->format('d-m-Y');
                    })
                    ->addColumn('time', function ($row) {
                        return getTime($row->created_at);
                    })
                    ->rawColumns(['phase', 'work_order_number', 'patient_name', 'completion_percent', 'doctor', 'file_number', 'date', 'time'])
                    ->make(true);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return view('landing.work_orders.search');
    }
}
