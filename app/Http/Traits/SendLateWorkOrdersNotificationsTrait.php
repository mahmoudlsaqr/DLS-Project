<?php

namespace App\Http\Traits;

use App\Models\User;
use App\Models\WorkOrdersLog;
use Illuminate\Support\Facades\DB;
use App\Notifications\LateWorkOrdersNotification;

trait SendLateWorkOrdersNotificationsTrait
{
    public function sendLateWorkOrdersNotifications()
    {
        $notifications = WorkOrdersLog::with([
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
                $workOrderNumber = $workOrder?->work_order_number;

                if (!$phase?->number_of_times_of_delay) return null;

                $delayHours = (int) $phase->number_of_times_of_delay;
                if (now()->diffInHours($log->created_at) < $delayHours) return null;

                $phaseName = $phase?->name_ar;
                return [
                    'id' => $log->id,
                    'work_order_id' => $workOrder?->id,
                    'work_order_number' => $workOrderNumber,
                    'user_id' => $log->users?->id,
                    'phase_id' => $phase?->id,
                    'phase_name' => $phaseName,
                    'created_at' => $log->created_at,
                    'title' => "تنويه: تأخر تنفيذ {$phaseName} من أمر العمل رقم {$workOrderNumber}، {$log->created_at->diffForHumans()}",
                    'since' => $log->created_at->diffForHumans(),
                ];
            })
            ->filter()
            ->values();

        $users = User::whereHas('roles.permissions', fn($q) => $q->where('name', 'view_notifications'))->get();
        $owner = User::find(1);

        foreach ($notifications as $notification) {
            $alreadySent = DB::table('notifications')
                ->where('type', LateWorkOrdersNotification::class)
                ->where('data->work_order_id', $notification['work_order_id'])
                ->where('data->user_id', $notification['user_id'])
                ->where('data->phase_id', $notification['phase_id'])
                ->exists();

            if (!$alreadySent) {
                $owner->notify(new LateWorkOrdersNotification($notification));

                foreach ($users as $user) {
                    $phone = trim($user->country_code) . trim($user->mobile);

                    if (!empty($user->country_code) && !empty($user->mobile)) {
                        $alreadySentWhatsapp = DB::table('whatsapp_notifications_logs')
                            ->where('user_id', $user->id)
                            ->where('work_order_id', $notification['work_order_id'])
                            ->where('phase_id', $notification['phase_id'])
                            ->exists();

                        if (!$alreadySentWhatsapp) {
                            $body = "تنويه: تأخر تنفيذ {$notification['phase_name']} من أمر العمل رقم {$notification['work_order_number']}، {$notification['since']}";

                            sendWhatsappMessage($body, $phone, LateWorkOrdersNotification::class, [
                                'user_id'       => $user->id,
                                'work_order_id' => $notification['work_order_id'],
                                'phase_id'      => $notification['phase_id'],
                            ]);
                        }
                    }
                }
            }
        }
    }
}
