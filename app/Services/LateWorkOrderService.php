<?php

namespace App\Services;

use App\Models\WorkOrdersLog;

class LateWorkOrderService
{
    public function getLateNotifications()
    {
        return WorkOrdersLog::with([
            'workOrders:id,client_id,doctor_id,patient_name,work_order_number,work_order_date,file_number,branch_id',
            'workOrders.clients:id,name_ar,name_en',
            'workOrders.doctors:id,name_ar,name_en',
            'workOrders.branches:id,name_ar,name_en',
            'phases:id,name_ar,name_en,number_of_times_of_delay',
            'users:id,name',
        ])
            ->where('phase_id', '<', 16)
            ->latest('created_at')
            ->get()
            ->groupBy('work_order_id')
            ->map(function ($logs) {
                $log = $logs->first();
                $phase = $log->phases;
                $workOrder = $log->workOrders;

                if (!$phase?->number_of_times_of_delay) return null;

                $phaseName = $phase?->name_ar;
                $delayHours = (int) $phase->number_of_times_of_delay;
                $hoursSinceLog = now()->diffInHours($log->created_at);

                if ($hoursSinceLog < $delayHours) {
                    return null;
                }

                $sinceDateTime = $log->created_at->addHours($delayHours);
                $diff = now()->diff($sinceDateTime);

                $sinceHours = $diff->h + ($diff->days * 24);
                $sinceMinutes = $diff->i;

                $sinceText = 'تأخرت لمدة ';

                if ($sinceHours > 0) {
                    $sinceText .= $sinceHours . ' ' . ($sinceHours == 1 ? 'ساعة' : 'ساعات');
                }

                if ($sinceMinutes > 0) {
                    if ($sinceHours > 0) $sinceText .= ' و';
                    $sinceText .= $sinceMinutes . ' ' . ($sinceMinutes == 1 ? 'دقيقة' : 'دقائق');
                }

                if ($sinceHours == 0 && $sinceMinutes == 0) {
                    $sinceText = 'تأخرت منذ لحظات';
                }

                return [
                    'id' => $log->id,
                    'work_order_id' => $workOrder?->id,
                    'work_order_number' => $workOrder?->work_order_number,
                    'user' => $log->users?->name .' '. $log->created_at->diffForHumans(),
                    'user_id' => $log->users?->id,
                    'phase_id' => $phase?->id,
                    'phase_name' => $phaseName,
                    'created_at' => $log->created_at,
                    'title' => "تنويه: تأخر تنفيذ «{$phaseName}» من أمر العمل رقم {$workOrder?->work_order_number}، {$sinceText}",
                    'since' => now()->diffInHours($log->created_at) - $delayHours,
                ];
            })
            ->filter()
            ->values();
    }

    public function getLateTable()
    {
        return WorkOrdersLog::with([
            'workOrders:id,client_id,doctor_id,patient_name,work_order_number,work_order_date,file_number,branch_id',
            'workOrders.clients:id,name_ar,name_en',
            'workOrders.doctors:id,name_ar,name_en',
            'workOrders.branches:id,name_ar,name_en',
            'phases:id,name_ar,name_en,number_of_times_of_delay',
            'users:id,name'
        ])
            ->where('phase_id', '<', 16)
            ->latest('created_at')
            ->get()
            ->groupBy('work_order_id')
            ->map(function ($logs) {
                $log = $logs->first();
                $phase = $log->phases;
                $workOrder = $log->workOrders;
                $workOrderNumber = $workOrder->work_order_number;

                if (!$phase?->number_of_times_of_delay) return null;

                $delayHours = (int) $phase->number_of_times_of_delay;
                if (now()->diffInHours($log->created_at) < $delayHours) return null;

                $phaseName = getLocaleName($phase, 'name_ar', 'name_en');

                $buttons = '';
                if (auth()->user()->can('update phase_work_orders_management')) {
                    $buttons .= '<div class="menu-item px-2">
                                <a class="menu-link px-3 update_phase" data-id="' . $workOrder?->id . '">' . __("Update phase") . '</a>
                          </div>';
                }

                return [
                    'id' => $log->id,
                    'work_order_id' => $workOrder?->id,
                    'work_order_number' => $workOrderNumber,
                    'work_order_date' => getDateDMY($workOrder?->work_order_date),
                    'user_id' => $log->users?->id,
                    'phase_id' => $phase?->id,
                    'phase_name' => $phaseName,
                    'client' => $workOrder?->clients ? getLocaleName($workOrder->clients, 'name_ar', 'name_en') : null,
                    'doctor' => $workOrder?->doctors ? getLocaleName($workOrder->doctors, 'name_ar', 'name_en') : null,
                    'patient_name' => $workOrder?->patient_name,
                    'file_number' => $workOrder?->file_number,
                    'branch' => $workOrder?->branches ? getLocaleName($workOrder->branches, 'name_ar', 'name_en') : null,
                    'created_at' => $log->created_at,
                    'last_update' => WhoDidItAndWhen($log->created_at, $log->users),
                    'title' => "تنويه: تأخر تنفيذ «{$phaseName}» من أمر العمل رقم {$workOrderNumber}، {$log->created_at->diffForHumans()}",
                    'since' => $log->created_at->diffForHumans(),
                    'actions' => '
                        <div class="btn-group dropstart">
                            <a class="btn btn-icon btn-sm" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical fs-3"></i>
                            </a>
                            <ul class="dropdown-menu menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-150px py-2" data-kt-menu="true">
                                ' . $buttons . '
                            </ul>
                        </div>
                    ',
                ];
            })
            ->filter()
            ->values();
    }
}
