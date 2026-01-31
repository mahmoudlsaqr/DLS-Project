<?php

namespace App\Http\Controllers\Cases\Orders;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cases\OrdersRequest;
use App\Http\Traits\TablesBuilderTraits\Cases\Orders\OrderAttachmentViewTable;
use App\Http\Traits\TablesBuilderTraits\Cases\Orders\OrdersTable;
use App\Models\Order;
use App\Models\OrderAttachment;
use App\Models\OrderNote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Exception;

class OrdersController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_orders', ['only' => ['index', 'view']]);
        $this->middleware('permission:create_orders', ['only' => ['create','store']]);
        $this->middleware('permission:update_orders', ['only' => ['update','edit']]);
        $this->middleware('permission:remove_orders', ['only' => ['destroy', 'destroy_all']]);
        $this->middleware('permission:accept_orders', ['only' => ['accept_status']]);
        $this->middleware('permission:reject_orders', ['only' => ['reject_status']]);
        $this->middleware('permission:approve_orders', ['only' => ['reject_status']]);
    }

    use OrdersTable;
    use OrderAttachmentViewTable;

    public function create()
    {
        return view("cases.orders.create", [
            "users" => User::where('status', '=', 'true')->get(),
        ]);
    }

    public function create_case(string $id)
    {
        return view("cases.cases_list.create", [
            "users" => User::where('status', '=', 'true')->get(),
            "Order" => Order::find($id),
        ]);
    }

    public function store(OrdersRequest $request)
    {
        $lock = Cache::lock('OrdersLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                $AttachmentsRepeater = $request["attachments_repeater"];

                $order = Order::create( $request ->all() );
                $order ->update([ 'created_by' => Auth::id() ]);

                $orderID = $order["id"];

                if (count($AttachmentsRepeater) > 0) {
                    $seq = 0;
                    foreach ($AttachmentsRepeater as $item) {
                        if (isset($item["file"]) && isset($item['type'])) {
                            $request ->validate([
                                'attachments_repeater.'.$seq.'.type' => 'required',
                                'attachments_repeater.'.$seq.'.file' => 'required|mimes:jpg,jpeg,png,mp4,wmv,mpeg,mpg,avi,pdf,stl,tl,bin',
                            ]);
                            $File = $item["file"];
                            $Type = $item['type'];
                            $Description = $item['description'];
                            $fileSize = filesize($File);
                            $request->file($File);
                            $new_file_name = time() . "_" . $orderID . "_" . $seq . "." . $File->getClientOriginalExtension();
                            Storage::disk('uploads')->put("order_attachments/".$orderID."/".$new_file_name, file_get_contents($File));
                            OrderAttachment::create([ "order_id" => $orderID, "file" => $new_file_name, "type" => $Type,"description" => $Description,"filesize" => $fileSize]);
                        }
                        ++$seq;
                    }
                }

                $patientName = $order["patient_name"];
                $physician = User::Where("id", $order["created_by"]) ->first();
                if ($physician->hasRole("physician")) {
                    $supervising = User::Where("id", $physician["supervising"]) ->first();
                    $supervisingMobile = $supervising["mobile"];
                    $supervisingCountryCode = $supervising["country_code"];

                    $supervisingMobile = $supervisingCountryCode.$supervisingMobile;

                    $supervisingName = $supervising["name"];
                    $supervisingName = strtolower($supervisingName);
                }

                $patientName = strtolower($patientName);
                $physicianName = strtolower($physician["name"]);
                $physicianMobile = $physician["mobile"];
                $physicianCountryCode = $physician["country_code"];

                $physicianMobile = $physicianCountryCode.$physicianMobile;

                if (settings("new_order_message_to_physician") != null) {
                    $bodyContent = settings("new_order_message_to_physician");
                    $replacements = [
                        '{physician}' => $physicianName,
                        '{supervising}' => $supervisingName ?? "",
                        '{patient_name}' => $patientName,
                        '{user}' => auth()->user()->name,
                        '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                        '{order_number}' => $orderID,
                    ];
                    $body = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
                } else {
                    $body = " تم رفع طلبكم رقم ".$orderID." للمريض ".$patientName." في انتظار اعتماد الطلب ";
                }

                sendWhatsappMessage($body, $physicianMobile, Order::class);
                if (settings("sms_check") == 1) { SMSMessage($body, $physicianMobile); }

                if ($physician->hasRole("physician")) {
                    if (!empty($supervising)) {

                        if (settings("new_order_message") != null) {
                            $bodyContent = settings("new_order_message");
                            $replacements = [
                                '{user}' => auth()->user()->name,
                                '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                                '{physician}' => $physicianName,
                                '{supervising}' => $supervisingName ?? "",
                                '{patient_name}' => $patientName,
                                '{order_number}' => $orderID,
                            ];
                            $body = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
                        } else {
                            $body = " طلب الطبيب ".$physicianName." للمريض ".$patientName." تم رفعه برقم ".$orderID." في انتظار اعتماد الطلب ";
                        }

                        if (isset($supervisingMobile)) {
                            $supervisingPhone = $supervisingMobile;

                            sendWhatsappMessage($body, $supervisingPhone, Order::class);
                            if (settings("sms_check") == 1) { SMSMessage($body, $supervisingPhone); }
                        }
                    }
                }

                $users = User::where("receiving_orders", 1)->get();
                if (count($users)>0) {
                    foreach ($users as $item) {
                        if ($item->hasRole(["owner", "admin"])) {
                            $userMobile = $item["mobile"];
                            $userCountryCode = $item["country_code"];
                            $userMobile = $userCountryCode.$userMobile;

                            if (settings("new_order_message") != null) {
                                $bodyContent = settings("new_order_message");
                                $replacements = [
                                    '{user}' => auth()->user()->name,
                                    '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                                    '{physician}' => $physicianName,
                                    '{supervising}' => $supervisingName ?? "",
                                    '{patient_name}' => $patientName,
                                    '{order_number}' => $orderID,
                                ];
                                $body = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
                            } else {
                                $body = " طلب الطبيب ".$physicianName." للمريض ".$patientName." تم تعديل الطلب برقم ".$orderID." في انتظار اعتماد الطلب ";
                            }

                            sendWhatsappMessage($body, $userMobile, Order::class);
                            if (settings("sms_check") == 1) { SMSMessage($body, $userMobile); }
                        }
                    }
                }

                return response() ->json(['success' => __('Orders created successfully')]);
            } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit(string $id)
    {
        return view("cases.orders.edit", [
            "OrderAttachment" => OrderAttachment::with("Orders")->where("order_id", $id)->where("type", 2)->get(),
            "users" => User::where('status', '=', 'true')->get(),
            "Order" => Order::find($id),
        ]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $orderID = $id;
            $order = Order::findOrFail($id);
            $order ->update( $request ->all() );
            $order ->update([ 'updated_by' => Auth::id(), 'status' => 1 ]);

            $physician = User::Where("id", $order["created_by"]) ->first();
            $patientName = $order["patient_name"];

            if (!empty($physician["supervising"])) {
                $supervising = User::Where("id", $physician["supervising"]) ->first();
            } else {
                $supervising = null;
            }

            $supervisingMobile = $supervising["mobile"];
            $supervisingCountryCode = $supervising["country_code"];

            $supervisingMobile = $supervisingCountryCode.$supervisingMobile;

            $supervisingName = $supervising["name"];
            $supervisingName = strtolower($supervisingName);

            $patientName = strtolower($patientName);
            $physicianName = strtolower($physician["name"]);

            if ($physician->hasRole("physician")) {
                if (!empty($supervising)) {

                    if (settings("order_update_message") != null) {
                        $bodyContent = settings("order_update_message");
                        $replacements = [
                            '{user}' => auth()->user()->name,
                            '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                            '{physician}' => $physicianName,
                            '{supervising}' => $supervisingName ?? "",
                            '{patient_name}' => $patientName,
                            '{order_number}' => $orderID,
                        ];
                        $body = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
                    } else {
                        $body = " طلب الطبيب ".$physicianName." للمريض ".$patientName." تم تعديل الطلب برقم ".$orderID." في انتظار اعتماد الطلب ";
                    }

                    if (isset($supervisingMobile)) {
                        sendWhatsappMessage($body, $supervisingMobile, Order::class);
                        if (settings("sms_check") == 1) { SMSMessage($body, $supervisingMobile); }
                    }
                }
            }

            return response()->json(['success' => __('Order updated successfully')]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function accept_status(Request $request, OrderNote $notes)
    {
        try {
            $request->validate([
                "designer" => "required",
                "notes" => "nullable|string",
            ]);

            $order = Order::find($request["id"]);
            $patientName = $order["patient_name"];

            $physician = User::findOrFail($order["created_by"]);
            if (!empty($physician["supervising"])) {
                $supervising = User::findOrFail($physician["supervising"]);
            } else {
                $supervising = null;
            }
            $designer = User::findOrFail($request["designer"]);
            $physicianName = $physician["name"];
            $supervisingName = $supervising["name"] ?? "";

            $patientName = strtolower($patientName);
            $physicianName = strtolower($physicianName);
            $supervisingName = strtolower($supervisingName);

            if ($supervising != null) {
                $supervisingMobile = $supervising["mobile"];
                $supervisingCountryCode = $supervising["country_code"];
                $supervisingMobile = $supervisingCountryCode . $supervisingMobile;
            }

            $order ->update([ 'status' => 2, "designer" => $designer["id"], "accepted_by" => Auth::id() ]);

            $notes->create([
                'order_id' => $request->input('id'),
                'notes' => $request->input('notes'),
                'created_by' => Auth::id(),
            ]);

            if ($physician != null) {
                $physicianMobile = $physician["mobile"];
                $physicianCountryCode = $physician["country_code"];

                $physicianMobile = $physicianCountryCode.$physicianMobile;

                if (settings("order_acceptance_message_to_physician") != null) {
                    $bodyContent = settings("order_acceptance_message_to_physician");
                    $replacements = [
                        '{user}' => auth()->user()->name,
                        '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                        '{physician}' => $physicianName,
                        '{supervising}' => $supervisingName,
                        '{patient_name}' => $patientName,
                        '{order_number}' => $order["id"],
                    ];
                    $body = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
                } else {
                    $body = "تم قبول طلبكم رقم ".$order["id"]." للمريض ".$patientName." وإرساله الى المصمم للبدء في تصميم الحالة.";
                }

                sendWhatsappMessage($body, $physicianMobile, Order::class);
                if (settings("sms_check") == 1) { SMSMessage($body, $physicianMobile); }
            }

            if ($designer != null) {
                $designerMobile = $designer["mobile"];
                $designerCountryCode = $designer["country_code"];
                $designerMobile = $designerCountryCode.$designerMobile;

                if (settings("order_acceptance_message_to_designer") != null) {
                    $bodyContent = settings("order_acceptance_message_to_designer");
                    $replacements = [
                        '{user}' => auth()->user()->name,
                        '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                        '{physician}' => $physicianName,
                        '{supervising}' => $supervisingName,
                        '{patient_name}' => $patientName,
                        '{order_number}' => $order["id"],
                    ];
                    $body = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
                } else {
                    $body = "تم قبول الطلب رقم ".$order["id"]." للطبيب ".$physicianName." الخاص بالمريض ".$patientName." ونرجوا البدء بتصميم الحالة ";
                }

                sendWhatsappMessage($body, $designerMobile, Order::class);
                if (settings("sms_check") == 1) { SMSMessage($body, $designerMobile); }
            }

            return response() ->json(['success' => __('Order status is accepted successfully')]);
        } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function reject_status(Request $request, OrderNote $notes)
    {
        try {
            $order = Order::find($request["id"]);
            $patientName = $order["patient_name"];

            $physician = User::Where("id", $order["created_by"]) ->first();
            $supervising = User::Where("id", $physician["supervising"]) ->first();
            $physicianName = $physician["name"];
            $supervisingName = $supervising["name"];

            $patientName = strtolower($patientName);
            $physicianName = strtolower($physicianName);
            $supervisingName = strtolower($supervisingName);

            if ($supervising != null) {
                $supervisingMobile = $supervising["mobile"];
                $supervisingCountryCode = $supervising["country_code"];
                $supervisingMobile = $supervisingCountryCode . $supervisingMobile;
            }

            $order ->update([ 'status' => 4, "rejected_by" => Auth::id() ]);

            $notes->create([
                'order_id' => $request->input('id'),
                'notes' => $request->input('notes'),
                'created_by' => Auth::id(),
            ]);

            $patientName = strtolower($patientName);
            $physicianName = strtolower($physicianName);

            if ($physician != null) {
                $physicianMobile = $physician["mobile"];
                $physicianCountryCode = $physician["country_code"];

                $physicianMobile = $physicianCountryCode.$physicianMobile;

                if (settings("order_acceptance_message_to_designer") != null) {
                    $bodyContent = settings("order_acceptance_message_to_designer");
                    $replacements = [
                        '{user}' => auth()->user()->name,
                        '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                        '{physician}' => $physicianName,
                        '{supervising}' => $supervisingName,
                        '{patient_name}' => $patientName,
                        '{order_number}' => $order["id"],
                    ];
                    $bodyContent = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
                } else {
                    $bodyContent = "تم رفض طلبكم رقم ".$order["id"]." للمريض ".$patientName." برجاء الإطلاع على الطلب لمعرفة السبب.";
                }

                $body = str_replace(["&nbsp;", " ", "\n", "\r\n", "\r"], ["%20", "%20", "%0A", "%0A", "%0A"], $bodyContent);

                sendWhatsappMessage($body, $physicianMobile, Order::class);
                if (settings("sms_check") == 1) { SMSMessage($body, $physicianMobile); }
            }

            return response() ->json(['success' => __('Order status is rejected successfully')]);
        } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id)
    {
        try {
            $data = Order::find($id);
            Storage::disk('uploads') ->deleteDirectory("order_attachments/".$id);
            $data ->delete();

            return response() ->json(['success' => __('Order deleted successfully')]);
        } catch (Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }

    public function destroy_all(Request $request)
    {
        try {
            $identities = $request ->get("id");

            foreach ($identities as $id) {
                $data = Order::find($id);
                Storage::disk('uploads') ->deleteDirectory("order_attachments/".$id);
                $data ->delete();
            }

            return response() ->json(['success' => __('Selected records deleted successfully')]);
        } catch (Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
