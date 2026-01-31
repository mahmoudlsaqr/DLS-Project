<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Services\LateWorkOrderService;
use App\Notifications\LateWorkOrdersNotification;
use Exception;

class SendLateNotificationsCommand extends Command
{
    protected $signature = 'notifications:send-late';

    protected $description = 'Send late work order notifications via WhatsApp';

    public function handle(LateWorkOrderService $service)
    {
        if (Cache::has('sending_late_notifications')) {
            $this->info('Already sending. Exiting...');
            return;
        }
        Cache::put('sending_late_notifications', true, now()->addMinutes(4));

        try {
            $notifications = $service->getLateNotifications();
            $users = User::whereHas('roles.permissions', fn($q) => $q->where('name', 'view_notifications'))->get();

            foreach ($notifications as $notification) {
                foreach ($users as $user) {
                    $phone = trim($user->country_code) . trim($user->mobile);

                    if (!empty($user->country_code) && !empty($user->mobile)) {
                        $alreadySentWhatsapp = DB::table('whatsapp_notifications_logs')
                            ->where('type', LateWorkOrdersNotification::class)
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

            $this->info('Late notifications sent successfully.');
        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        } finally {
            Cache::forget('sending_late_notifications');
        }
    }
}
