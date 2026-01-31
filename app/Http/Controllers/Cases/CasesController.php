<?php

namespace App\Http\Controllers\Cases;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cases\CaseRequest;
use App\Http\Traits\TablesBuilderTraits\Cases\CaseAttachmentViewTable;
use App\Http\Traits\TablesBuilderTraits\Cases\CasesTable;
use App\Models\CaseAttachment;
use App\Models\Cases;
use App\Models\Order;
use App\Models\ReceiptVoucher;
use App\Models\CasesNotes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Exception;

class CasesController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_cases', ['only' => ['index', 'view']]);
        $this->middleware('permission:create_cases', ['only' => ['create','store']]);
        $this->middleware('permission:update_cases', ['only' => ['update','edit']]);
        $this->middleware('permission:remove_cases', ['only' => ['destroy', 'destroy_all']]);
        $this->middleware('permission:accept_cases', ['only' => ['accept_status']]);
        $this->middleware('permission:reject_cases', ['only' => ['reject_status']]);
        $this->middleware('permission:create_receipt_vouchers', ['only' => ['store_receipt_voucher']]);
        $this->middleware('permission:print_receipt_vouchers', ['only' => ['receipt_voucher']]);
    }

    use CasesTable;
    use CaseAttachmentViewTable;

    public function create()
    {
        return view("cases.cases_list.create", [
            "users" => User::where('status', '=', 'true')->get(),
        ]);
    }

    public function store(CaseRequest $request)
    {
        $lock = Cache::lock('CasesLock', LOOK_COUNT);
        if ($lock->get()) {
            try {
                $attachmentsRepeater = $request["attachments_repeater"];

                $case = Cases::create($request->all());
                $caseID = $case["id"];
                $caseNumber = $case["case_number"];
                $patientName = strtolower($case["patient_name"] ?? "");
                $physician = User::find($case["physician"]);

                if (!empty($physician["supervising"])) {
                    $supervising = User::find($physician["supervising"]);
                } else {
                    $supervising = null;
                }

                $isSkipping = (int)$supervising["skipping"] === 1;

                $case->update([
                    'status' => $isSkipping? 2 : 1,
                    'created_by' => Auth::id(),
                    'supervising' => $supervising->id ?? null,
                ]);

                $designer = User::findOrFail($case["created_by"]);

                $physicianMobile = $physician["country_code"] . $physician["mobile"];
                $supervisingMobile = $supervising->country_code . $supervising->mobile;

                $physicianName = $physician["name"] ?? "";
                $supervisingName = $supervising["name"] ?? "";
                $designerName = $designer["name"] ?? "";

                if (count($attachmentsRepeater) > 0) {
                    $seq = 0;
                    foreach ($attachmentsRepeater as $item) {
                        if (isset($item["file"]) && isset($item['type'])) {
                            $request->validate([
                                'attachments_repeater.'.$seq.'.type' => 'required',
                                'attachments_repeater.'.$seq.'.file' => 'required|mimes:jpg,jpeg,png,mp4,wmv,mpeg,mpg,avi,pdf,stl,tl,bin',
                            ]);
                            $File = $item["file"];
                            $Type = $item['type'];
                            $Description = $item['description'];
                            $fileSize = filesize($File);
                            $request->file($File);
                            $new_file_name = time() . "_" . $caseID . "_" . $seq . "." . $File->getClientOriginalExtension();
                            Storage::disk('uploads')->put("case_attachments/".$case["case_number"]."/".$new_file_name, file_get_contents($File));
                            CaseAttachment::create([ "case_id" => $caseID, "file" => $new_file_name, "type" => $Type,"description" => $Description,"filesize" => $fileSize]);
                        }
                        ++$seq;
                    }
                }

                if (isset($request["order_id"])) {
                    $order = Order::find($request["order_id"]);

                    // Update order status
                    $order->update(['status' => 3]);

                    // Prepare message content for order approval
                    $bodyContent = settings("order_approval_message") ?? "تم اعتماد طلبكم رقم {$order["id"]} للمريض {$patientName} وتم تصميم الحالة.";
                    $this->sendNotification($bodyContent, $physicianMobile, $physicianName, $supervisingName, $designerName, $patientName, $caseNumber, Order::class);
                }

                if ($isSkipping) {
                    $messageTemplate = settings("case_acceptance_message_to_physician") ?? "تم وضع الخطه العلاجيه واعتمادها لحالة المريض {$patientName} رقم الحالة {$caseNumber}، في انتظار الموافقه علي الخطة من قبلكم.";
                    $this->sendNotification($messageTemplate, $physicianMobile, $physicianName, $supervisingName, $designerName, $patientName, $caseNumber);

                    // Prepare message content for the supervising physician
                    $messageTemplate = settings("new_case_message") ?? " حالة الطبيب {$physician["name"]} للمريض {$patientName} تم رفعها برقم {$caseNumber} في انتظار اعتماد الخطه العلاجيه ";
                    $this->sendNotification($messageTemplate, $supervisingMobile, $physician["name"], $supervising["name"], $designer["name"], $patientName, $caseNumber);
                } else {
                    // Prepare message content for the supervising physician when not skipping
                    $messageTemplate = settings("new_case_message") ?? " حالة الطبيب {$physician["name"]} للمريض {$patientName} تم رفعها برقم {$caseNumber} في انتظار اعتماد الخطه العلاجيه ";
                    $this->sendNotification($messageTemplate, $supervisingMobile, $physician["name"], $supervising["name"], $designer["name"], $patientName, $caseNumber);
                }

                return response()->json(['success' => __('Case created successfully')]);
            } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); } finally { $lock->release(); }
        } else { return response()->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    private function sendNotification($messageTemplate, $mobile, $physicianName, $supervisingName, $designerName, $patientName, $caseNumber, $type = Cases::class, array $extra = [])
    {
        $replacements = [
            '{physician}'    => strtolower($physicianName),
            '{supervising}'  => strtolower($supervisingName),
            '{designer}'     => strtolower($designerName),
            '{patient_name}' => strtolower($patientName),
            '{case_number}'  => $caseNumber,
            '{user}'         => auth()->user()?->name,
            '{center_name}'  => app()->getLocale() === "ar" ? settings('center_name_ar') : settings('center_name_en'),
        ];
        $body = str_replace(array_keys($replacements), array_values($replacements), $messageTemplate);

        sendWhatsappMessage($body, $mobile, $type, $extra);

        if (!empty($mobile) && settings('sms_check') == 1) {
            SMSMessage($body, $mobile);
        }
    }

    public function edit(string $id)
    {
        return view("cases.cases_list.edit", [
            "CaseAttachment" => CaseAttachment::with("Cases")->where("case_id", $id)->where("type", 2)->get(),
            "users" => User::where('status', '=', 'true')->get(),
            "Case" => Cases::find($id),
        ]);
    }

    public function update(CaseRequest $request, CasesNotes $notes, string $id)
    {
        try {
            $case = Cases::findOrFail($id);

            $case->update($request->all());
            $currentStatus = $case->status;
            $newStatus = ($currentStatus == 4) ? 1 : (($currentStatus == 5) ? 2 : $currentStatus);

            $caseNumber = $case["case_number"];
            $patientName = $case["patient_name"];

            $physician = User::Where("id", $case["physician"])->first();
            $supervising = User::Where("supervising", $physician["supervising"])->first();
            $designer = User::Where("id", $case["created_by"])->first();

            $physicianMobile = $physician["mobile"];
            $physicianCountryCode = $physician["country_code"];

            $supervisingMobile = $supervising["mobile"];
            $supervisingCountryCode = $supervising["country_code"];

            $designerMobile = $designer["mobile"];
            $designerCountryCode = $designer['country_code'];

            $supervisingMobile = $supervisingCountryCode.$supervisingMobile;
            $physicianMobile = $physicianCountryCode.$physicianMobile;
            $designerMobile = $designerCountryCode.$designerMobile;

            $patientName = strtolower($patientName);
            $physicianName = strtolower($physician["name"]);
            $supervisingName = strtolower($supervising["name"]);
            $designerName = strtolower($designer["name"]);

            if ($request["case_note"]) {
                $notes->create([
                    'case_id' => $id,
                    'notes' => $request["case_note"],
                    'created_by' => Auth::id(),
                ]);
            }

            $case->update([
                'status' => $newStatus,
                'updated_by' => Auth::id(),
                'supervising' => $supervising["id"],
            ]);

            if (settings("case_update_message") != null) {
                $bodyContent = settings("case_update_message");
                $replacements = [
                    '{user}' => auth()->user()->name,
                    '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                    '{physician}' => $physicianName,
                    '{supervising}' => $supervisingName,
                    '{designer}' => $designerName,
                    '{patient_name}' => $patientName,
                    '{case_number}' => $caseNumber,
                ];
                $body = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
            } else {
                $body = " تم تعديل الحالة رقم ".$caseNumber." للمريض ".$patientName." للطبيب ".$physicianName." برجاء الاطلاع ";
            }

            if ($currentStatus == 4) {
                $phone = $supervisingMobile;

                sendWhatsappMessage($body, $phone, Cases::class);
                if (settings("sms_check") == 1) { SMSMessage($body, $phone); }
            } elseif ($currentStatus == 5) {
                $phone = $physicianMobile;

                sendWhatsappMessage($body, $phone, Cases::class);
                if (settings("sms_check") == 1) { SMSMessage($body, $phone); }
            }

            return response()->json(['success' => __('Case updated successfully')]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function accept_status(string $id)
    {
        try {
            $case = Cases::find($id);

            $caseStatus = $case["status"];
            $caseNumber = $case["case_number"];
            $patientName = $case["patient_name"];

            $physician = User::Where("id", $case["physician"])->first();
            $supervising = User::Where("id", $case["supervising"])->first();
            $designer = User::Where("id", $case["created_by"])->first();

            $physicianMobile = $physician["mobile"];
            $physicianCountryCode = $physician["country_code"];

            $supervisingMobile = $supervising["mobile"];
            $supervisingCountryCode = $supervising["country_code"];

            $designerMobile = $designer["mobile"];
            $designerCountryCode = $designer['country_code'];

            $supervisingMobile = $supervisingCountryCode.$supervisingMobile;
            $physicianMobile = $physicianCountryCode.$physicianMobile;
            $designerMobile = $designerCountryCode.$designerMobile;

            $patientName = strtolower($patientName);
            $physicianName = strtolower($physician["name"]);
            $supervisingName = strtolower($supervising["name"]);
            $designerName = strtolower($designer["name"]);

            $user = Auth::user();
            $role = $user->roles->value("name");

            if ($role === "supervising") {
                $case->update([ 'status' => 2 ]);

                if (settings("case_acceptance_message_to_supervisor") != null) {
                    $bodyContent = settings("case_acceptance_message_to_supervisor");
                    $replacements = [
                        '{user}' => auth()->user()->name,
                        '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                        '{physician}' => $physicianName,
                        '{supervising}' => $supervisingName,
                        '{designer}' => $designerName,
                        '{patient_name}' => $patientName,
                        '{case_number}' => $caseNumber,
                    ];
                    $body = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
                } else {
                    $body = "تم وضع الخطه العلاجيه واعتمادها لحالة المريض ".$patientName." رقم الحالة ".$caseNumber."، في انتظار الموافقه علي الخطة من قبلكم.";
                }

                if ($caseStatus == 1) {
                    $phone = $physicianMobile;

                    sendWhatsappMessage($body, $phone, Cases::class);
                    if (settings("sms_check") == 1) { SMSMessage($body, $phone); }
                } elseif ($caseStatus == 2) {
                    $phone = $designerMobile;

                    sendWhatsappMessage($body, $phone, Cases::class);
                    if (settings("sms_check") == 1) { SMSMessage($body, $phone); }
                }

            } elseif ($role === "physician") {
                $case->update([ 'status' => 3 ]);

                if (settings("case_acceptance_message_to_physician") != null) {
                    $bodyContent = settings("case_acceptance_message_to_physician");
                    $replacements = [
                        '{user}' => auth()->user()->name,
                        '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                        '{physician}' => $physicianName,
                        '{supervising}' => $supervisingName,
                        '{designer}' => $designerName,
                        '{patient_name}' => $patientName,
                        '{case_number}' => $caseNumber,
                    ];
                    $body = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
                } else {
                    $body = "رقم الحالة: ".$caseNumber." تمت الموافقة عليه للطبيب: ".$physicianName." المريض: ".$patientName." جاهز للاستكمال. يرجى الانتهاء منه في أقرب وقت ممكن ";
                }

//                $body = str_replace(["&nbsp;", " ", "\n", "\r\n", "\r"], ["%20", "%20", "%0A", "%0A", "%0A"], $body);
                $phone = $designerMobile;

                sendWhatsappMessage($body, $phone, Cases::class);
                if (settings("sms_check") == 1) { SMSMessage($body, $phone); }

            }

            return response()->json(['success' => __('Case status is accepted successfully')]);
        } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }

    public function reject_status(Request $request, CasesNotes $notes)
    {
        try {
            $case = Cases::find($request->input('id'));

            $caseNumber = $case["case_number"];
            $patientName = $case["patient_name"];

            $physician = User::Where("id", $case["physician"])->first();
            $supervising = User::Where("id", $case["supervising"])->first();
            $designer = User::Where("id", $case["created_by"])->first();

            $physicianMobile = $physician["mobile"];
            $physicianCountryCode = $physician["country_code"];

            $supervisingMobile = $supervising["mobile"];
            $supervisingCountryCode = $supervising["country_code"];

            $designerMobile = $designer["mobile"];
            $designerCountryCode = $designer['country_code'];

            $physicianMobile = $physicianCountryCode.$physicianMobile;
            $supervisingMobile = $supervisingCountryCode.$supervisingMobile;
            $designerMobile = $designerCountryCode.$designerMobile;

            $patientName = strtolower($patientName);
            $physicianName = strtolower($physician["name"]);
            $supervisingName = strtolower($supervising["name"]);
            $designerName = strtolower($designer["name"]);

            $user = Auth::user();
            $role = $user->roles->value('name');

            $request->validate([
                'reason_for_rejection' => 'nullable|string',
            ]);

            $notes->create([
                'case_id' => $request->input('id'),
                'notes' => $request->input('notes'),
                'created_by' => Auth::id(),
            ]);

            if ($role === 'supervising') {
                $case->update(['status' => 4]);
            } elseif ($role === 'physician') {
                $case->update(['status' => 5]);
            }

            if (settings("case_rejection_message") != null) {
                $bodyContent = settings("case_rejection_message");
                $replacements = [
                    '{user}' => auth()->user()->name,
                    '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                    '{physician}' => $physicianName,
                    '{supervising}' => $supervisingName,
                    '{designer}' => $designerName,
                    '{patient_name}' => $patientName,
                    '{case_number}' => $caseNumber,
                ];
                $body = str_replace(array_keys($replacements), array_values($replacements), $bodyContent);
            } else {
                $body = " تم إعادة الحالة رقم: {$caseNumber} إلى الطبيب {$physicianName} للمريض {$patientName} للتعديل وتلافي الملاحظات الموجودة. ";
            }

            $phone = $designerMobile;

            sendWhatsappMessage($body, $phone, Cases::class);
            if (settings("sms_check") == 1) { SMSMessage($body, $phone); }

            return response()->json(['success' => __('Case status is rejected successfully')]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store_receipt_voucher(Request $request)
    {
        $lock = Cache::lock('ReceiptVoucherLock', LOOK_COUNT);
        if ($lock->get()) {
            try {
                $request->validate([
                    "clinic_name" => "required|string",
                    "amount" => "required|numeric",
                    "description" => "required|string",
                ]);

                $Receipt = ReceiptVoucher::create( $request->all() );
                $Receipt->update([ 'created_by' => Auth::id() ]);

                return response()->json([
                    'success' => __('Receipt voucher created successfully'),
                    'case_id' => $Receipt["case_id"],
                ]);
            } catch (Exception $e) { return response()->json(['error' => $e->getMessage()], 500); } finally { $lock->release(); }
        } else { return response()->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function receipt_voucher(string $id)
    {
        $case = Cases::find($id);
        $Voucher = ReceiptVoucher::where("case_id", $id)->first();
        return view("cases.cases_list.receipt_voucher", [
            "Case" => $case,
            "Voucher" => $Voucher,
        ]);
    }

    public function destroy($id)
    {
        try {
            $data = Cases::find($id);
            Storage::disk('uploads')->deleteDirectory("case_attachments/".$data["case_number"]);
            $data->delete();

            return response()->json(['success' => __('Case deleted successfully')]);
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
            $identities = $request->get("id");

            foreach ($identities as $id) {
                $data = Cases::find($id);
                Storage::disk('uploads')->deleteDirectory("case_attachments/".$data["case_number"]);
                $data->delete();
            }

            return response()->json(['success' => __('Selected records deleted successfully')]);
        } catch (Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
