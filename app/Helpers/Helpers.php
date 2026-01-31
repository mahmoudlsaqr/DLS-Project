<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Settings;
use App\Models\User;
use App\Models\WorkOrdersLog;
use App\Models\WhatsappNotificationLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

if (!function_exists('settings')) {
    function settings($key)
    {
        $result = Settings::where('key', $key)->first();
        return ($result)? $result["value"] : '';
    }
}

function getTimeDate($date)
{
    $time = strtotime($date);
    return (date('d-m-Y', $time) ." ". date('h:m', $time)) . " ". ((date('A', $time) == "AM") ? __('AM') : __('PM'));
}
function getTime($date)
{
    $time = strtotime($date);
    return (date('h:m', $time)) . " ". ((date('A', $time) == "AM") ? __('AM') : __('PM'));
}
function getDateDMY($date)
{
    $date = strtotime($date);
    return (date('d-m-Y', $date));
}
function getDateYMD($date)
{
    $date = strtotime($date);
    return (date('Y-m-d', $date));
}

if (!function_exists('WhoDidItAndWhen')) {
    function WhoDidItAndWhen($timestamps, $user): string
    {
        if (empty($user) || empty($timestamps)) {
            return '';
        }

        if (!$timestamps instanceof DateTime) {
            try {
                $timestamps = new DateTime($timestamps);
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        $date = $timestamps->format('d-m-Y');
        $time = $timestamps->format('h:i');
        $DateTimeType = ($timestamps->format('A') === 'AM') ? __('AM') : __('PM');

        return '<span class="text-primary">' . e($user->name) . '</span><br>' .
            '<span>' . $date . ' <span class="text-gray-600 ms-1">' . $time . $DateTimeType . '</span></span>';
    }
}

if (!function_exists('route_ID_')) {
    function route_ID_(string $route)
    {
        return route($route, ['id' => '_ID_']);
    }
}

if (!function_exists('sendWhatsappMessage')) {
    function sendWhatsappMessage(string $body, string $phone, string $type = null, array $extraLogData = [])
    {
        if (settings("whatsapp_check") == 1 && !empty($phone) && !empty($body)) {
            if ((int) settings('whatsapp_check') !== 1) {
                return null;
            }

            $response = Http::get('https://www.2whats.com/api/send', [
                'mobile'     => env('WHATSAPP_MOBILE'),
                'password'   => env('WHATSAPP_PASSWORD'),
                'instanceid' => env('WHATSAPP_INSTANCE_ID'),
                'message'    => $body,
                'numbers'    => $phone,
                'json'       => 1,
                'type'       => 1,
            ]);
            $data = $response->json();

            $logData = [
                'mobile'   => $phone,
                'message'  => $body,
                'success'  => isset($data['success']) && $data['success'] == true,
                'response' => json_encode($data),
                'type'     => $type,
            ];
            $logData = array_merge($logData, $extraLogData);

            WhatsappNotificationLog::create($logData);
        }
    }
}

function SMSMessage($Body, $Phone) {
    $sender = urlencode("");
    $msg = $Body;
    $apikey = '';
    $username = "";
    $numbers = $Phone;
    $response = "100";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.mora-sa.com/api/v1/sendsms?");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
        "api_key=". $apikey. "&username=". $username. "&message=". $msg. "&sender=". $sender. "&numbers=". $numbers. "&response=". $response);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);
    curl_close($ch);

    echo nl2br($server_output);

    if ($server_output == "100") {
        echo json_encode(array("status" => "success", "message" => "Message sent successfully"));
    } else {
        echo json_encode(array("status" => "error", "message" => "Failed to send message"));
    }
}

function createAvatar($request, $varName, $id) {
    $request->file($varName);
    $imageName = time() . "_$id." . $request[$varName]->extension();
    $request[$varName]->storeAs('avatars', $imageName, 'uploads');
    User::where('id', $id)->update(['avatar' => $imageName]);
}

function updateAvatar($request, $varName, $oldAvatar, $id) {
    if ($request->hasFile($varName)) {
        if ($oldAvatar !== null) {
            Storage::disk('uploads')->delete($oldAvatar);
            if( File::exists(public_path(config('settings.avtar_file') . $oldAvatar))) {
                File::delete(public_path(config('settings.avtar_file') . $oldAvatar));
            }
        }
        createAvatar($request, $varName, $id);
    }
}

function registrationWorkOrderLog($workOrderId, $phaseId) {
    WorkOrdersLog::create([
        'work_order_id' => $workOrderId,
        'user_id' => Auth::id(),
        'log_date' => Carbon::now()->format('Y-m-d'),
        'log_time' => Carbon::now()->format('H:i:s'),
        'phase_id' => $phaseId,
    ]);
}

function getLocaleName($row, $name_ar, $name_en) {
    return (app()->getLocale() === 'ar') ? $row[$name_ar] : $row[$name_en];
}


function formatPhoneNumber($countryCode, $mobile)
{
    return $countryCode . $mobile;
}

function getCenterName()
{
    return app()->getLocale() === "ar" ? settings('center_name_ar') : settings('center_name_en');
}

function generateMessageContent($settingKey, $replacements, $default = "")
{
    $content = settings($settingKey) ?? $default;
    return str_replace(array_keys($replacements), array_values($replacements), $content);
}

function getDeliveryUserByBranch(int $branchId)
{
    return User::where('role', 'delivery')
        ->whereHas('branches', function ($query) use ($branchId) {
            $query->where('branch_id', $branchId);
        })
        ->get();
}

function isNotificationSent($workOrderId, $phaseId, $userId)
{
    return DB::table('sent_whatsapp_notifications')
        ->where('work_order_id', $workOrderId)
        ->where('phase_id', $phaseId)
        ->where('user_id', $userId)
        ->exists();
}

function markNotificationAsSent($workOrderId, $phaseId, $userId)
{
    DB::table('sent_whatsapp_notifications')->insert([
        'work_order_id' => $workOrderId,
        'phase_id' => $phaseId,
        'user_id' => $userId,
        'sent_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

if (!function_exists('getUser')) {
    function getUser(int $id)
    {
        if (empty($id)) {
            return '';
        }

        return User::find($id);
    }
}
if (!function_exists('userCan')) {
    function userCan(string $permission)
    {
        if (empty($permission)) {
            return false;
        }

        $user = auth()->user();
        return $user ? $user->can($permission) : false;
    }
}
if (!function_exists('getRole')) {
    function getRole($row, $name)
    {
        if (empty($row)) {
            return false;
        }

        return $row->roles->pluck($name)->implode(', ');
    }
}
