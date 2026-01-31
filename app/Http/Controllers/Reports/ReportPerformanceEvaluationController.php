<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Technicians;
use App\Models\User;
use App\Models\WorkOrdersLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportPerformanceEvaluationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_performance_evaluation_report', ['only' => ['index', 'report']]);
        $this->middleware('permission:print_performance_evaluation_report', ['only' => ['print']]);
    }

    public function index()
    {
        $technicianTargets = Technicians::pluck('monthly_target', 'user_id');
        $query = WorkOrdersLog::where('phase_id', 2)
            ->with(['users:id,name', 'workOrders:id,service_count']);

        $query = $query->get()->groupBy('work_order_id');

        $technicianStatsDone = $query->map(function ($logs) use ($technicianTargets) {
            $lastLog = $logs->last();
            return [
                'user_id' => $lastLog->user_id,
                'user_name' => $lastLog->users->name,
                'monthly_target' => $technicianTargets[$lastLog->user_id] ?? 0,
                'service_count' => $lastLog->workOrders->service_count ?? 0,
                'id' => $lastLog->workOrders->id ?? 0,
            ];
        })->groupBy('user_id')->map(function ($logs) {
            return [
                'id' => $logs->first()['id'],
                'user_id' => $logs->first()['user_id'],
                'user_name' => $logs->first()['user_name'],
                'monthly_target' => $logs->first()['monthly_target'],
                'work_orders_count_done' => $logs->count(),
                'sum_done' => $logs->sum('service_count'),
            ];
        });

        $returnedTechnicianStats = $query->filter(function ($logs) {
            return $logs->count() > 1; // إذا كان هناك أكثر من سجل، فهو معاد
        })->map(function ($logs) {
            $previousLog = $logs->slice(-2, 1)->first(); // الفني الذي عدّل الأمر قبل آخر شخص
            return [
                'user_id' => $previousLog->user_id,
                'service_count' => $previousLog->workOrders->service_count ?? 0,
            ];
        })->groupBy('user_id')->map(function ($logs) {
            return [
                'returned_work_orders_count' => $logs->count(),
                'returned_sum' => $logs->sum('service_count'),
            ];
        });

        $allTechnicians = User::select('id', 'name')->where('role', 'technician')->get()->mapWithKeys(function ($user) use ($technicianTargets) {
            return [
                $user->id => [
                    'name' => $user->name,
                    'monthly_target' => $technicianTargets[$user->id] ?? 0,
                ],
            ];
        });

        $allTechnicianStats = $allTechnicians->map(function ($data, $id) use ($technicianStatsDone, $returnedTechnicianStats) {
            $stats = array_merge(
                $technicianStatsDone[$id] ?? [
                'user_id' => $id,
                'user_name' => $data['name'],
                'monthly_target' => $data['monthly_target'],
                'work_orders_count_done' => 0,
                'sum_done' => 0,
            ],
                $returnedTechnicianStats[$id] ?? [
                'returned_work_orders_count' => 0,
                'returned_sum' => 0,
            ]
            );

            $stats['work_orders_count'] = $stats['work_orders_count_done'] + $stats['returned_work_orders_count'];
            $stats['sum'] = $stats['sum_done'] + $stats['returned_sum'];

            return $stats;
        });

        return view("reports.performance_evaluation_report.index", [
            'data' => $allTechnicianStats,
        ]);
    }

    public function report(Request $request)
    {
        $startDate = $request->input('start_date') ?? '';
        $endDate = $request->input('end_date') ?? '';
        $phaseId = $request->input('phase_id') ?? '';

        $technicianTargets = Technicians::pluck('monthly_target', 'user_id');
        $query = WorkOrdersLog::where('phase_id', 2)
            ->with(['users:id,name', 'workOrders:id,service_count']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $query = $query->get()->groupBy('work_order_id');

        $technicianStatsDone = $query->map(function ($logs) use ($technicianTargets) {
            $lastLog = $logs->last();
            return [
                'user_id' => $lastLog->user_id,
                'user_name' => $lastLog->users->name,
                'monthly_target' => $technicianTargets[$lastLog->user_id] ?? 0,
                'service_count' => $lastLog->workOrders->service_count ?? 0,
                'id' => $lastLog->workOrders->id ?? 0,
            ];
        })->groupBy('user_id')->map(function ($logs) {
            return [
                'id' => $logs->first()['id'],
                'user_id' => $logs->first()['user_id'],
                'user_name' => $logs->first()['user_name'],
                'monthly_target' => $logs->first()['monthly_target'],
                'work_orders_count_done' => $logs->count(),
                'sum_done' => $logs->sum('service_count'),
            ];
        });

        $returnedTechnicianStats = $query->filter(function ($logs) {
            return $logs->count() > 1; // إذا كان هناك أكثر من سجل، فهو معاد
        })->map(function ($logs) {
            $previousLog = $logs->slice(-2, 1)->first(); // الفني الذي عدّل الأمر قبل آخر شخص
            return [
                'user_id' => $previousLog->user_id,
                'service_count' => $previousLog->workOrders->service_count ?? 0,
            ];
        })->groupBy('user_id')->map(function ($logs) {
            return [
                'returned_work_orders_count' => $logs->count(),
                'returned_sum' => $logs->sum('service_count'),
            ];
        });

        $allTechnicians = User::select('id', 'name')->where('role', 'technician')->get()->mapWithKeys(function ($user) use ($technicianTargets) {
            return [
                $user->id => [
                    'name' => $user->name,
                    'monthly_target' => $technicianTargets[$user->id] ?? 0,
                ],
            ];
        });

        $allTechnicianStats = $allTechnicians->map(function ($data, $id) use ($technicianStatsDone, $returnedTechnicianStats) {
            $stats = array_merge(
                $technicianStatsDone[$id] ?? [
                'user_id' => $id,
                'user_name' => $data['name'],
                'monthly_target' => $data['monthly_target'],
                'work_orders_count_done' => 0,
                'sum_done' => 0,
            ],
                $returnedTechnicianStats[$id] ?? [
                'returned_work_orders_count' => 0,
                'returned_sum' => 0,
            ]
            );

            $stats['work_orders_count'] = $stats['work_orders_count_done'] + $stats['returned_work_orders_count'];
            $stats['sum'] = $stats['sum_done'] + $stats['returned_sum'];

            return $stats;
        });

        return view("reports.performance_evaluation_report.index", [
            'data' => $allTechnicianStats,
            'request' => $request,
        ]);
    }

    public function print(Request $request)
    {
        $startDate = $request->input('start_date') ?? '';
        $endDate = $request->input('end_date') ?? '';
        $phaseId = $request->input('phase_id') ?? '';

        $technicianTargets = Technicians::pluck('monthly_target', 'user_id');
        $query = WorkOrdersLog::where('phase_id', 2)
            ->with(['users:id,name', 'workOrders:id,service_count']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $query->whereDate('created_at', '=', $startDate);
        } elseif ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        $query = $query->get()->groupBy('work_order_id');

        $technicianStatsDone = $query->map(function ($logs) use ($technicianTargets) {
            $lastLog = $logs->last();
            return [
                'user_id' => $lastLog->user_id,
                'user_name' => $lastLog->users->name,
                'monthly_target' => $technicianTargets[$lastLog->user_id] ?? 0,
                'service_count' => $lastLog->workOrders->service_count ?? 0,
                'id' => $lastLog->workOrders->id ?? 0,
            ];
        })->groupBy('user_id')->map(function ($logs) {
            return [
                'id' => $logs->first()['id'],
                'user_id' => $logs->first()['user_id'],
                'user_name' => $logs->first()['user_name'],
                'monthly_target' => $logs->first()['monthly_target'],
                'work_orders_count_done' => $logs->count(),
                'sum_done' => $logs->sum('service_count'),
            ];
        });

        $returnedTechnicianStats = $query->filter(function ($logs) {
            return $logs->count() > 1; // إذا كان هناك أكثر من سجل، فهو معاد
        })->map(function ($logs) {
            $previousLog = $logs->slice(-2, 1)->first(); // الفني الذي عدّل الأمر قبل آخر شخص
            return [
                'user_id' => $previousLog->user_id,
                'service_count' => $previousLog->workOrders->service_count ?? 0,
            ];
        })->groupBy('user_id')->map(function ($logs) {
            return [
                'returned_work_orders_count' => $logs->count(),
                'returned_sum' => $logs->sum('service_count'),
            ];
        });

        $allTechnicians = User::select('id', 'name')->where('role', 'technician')->get()->mapWithKeys(function ($user) use ($technicianTargets) {
            return [
                $user->id => [
                    'name' => $user->name,
                    'monthly_target' => $technicianTargets[$user->id] ?? 0,
                ],
            ];
        });

        $allTechnicianStats = $allTechnicians->map(function ($data, $id) use ($technicianStatsDone, $returnedTechnicianStats) {
            $stats = array_merge(
                $technicianStatsDone[$id] ?? [
                'user_id' => $id,
                'user_name' => $data['name'],
                'monthly_target' => $data['monthly_target'],
                'work_orders_count_done' => 0,
                'sum_done' => 0,
            ],
                $returnedTechnicianStats[$id] ?? [
                'returned_work_orders_count' => 0,
                'returned_sum' => 0,
            ]
            );

            $stats['work_orders_count'] = $stats['work_orders_count_done'] + $stats['returned_work_orders_count'];
            $stats['sum'] = $stats['sum_done'] + $stats['returned_sum'];

            return $stats;
        });

        return view("reports.performance_evaluation_report.print", [
            'data' => $allTechnicianStats,
            'request' => $request,
        ]);
    }
}
