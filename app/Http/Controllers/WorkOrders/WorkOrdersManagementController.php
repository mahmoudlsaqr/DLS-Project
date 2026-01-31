<?php

namespace App\Http\Controllers\WorkOrders;

use App\Http\Controllers\Controller;
use App\Http\Requests\WorkOrders\WorkOrdersManagementRequest;
use App\Http\Requests\WorkOrders\WorkOrdersManagementUpdateRequest;
use App\Http\Traits\TablesBuilderTraits\WorkOrders\LogPhasesWorkOrdersTable;
use App\Http\Traits\TablesBuilderTraits\WorkOrders\LogWorkOrdersTable;
use App\Http\Traits\TablesBuilderTraits\WorkOrders\WorkOrdersManagementTable;
use App\Models\Branch;
use App\Models\Clients;
use App\Models\ColorCodes;
use App\Models\Doctors;
use App\Models\DoctorsRequest;
use App\Models\DoctorsServicesDetails;
use App\Models\Services;
use App\Models\SpecialOffers;
use App\Models\Technicians;
use App\Models\WorkingPhases;
use App\Models\WorkOrdersLog;
use App\Models\WorkOrdersManagement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
use Carbon\Carbon;

class WorkOrdersManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_work_orders_management', ['only' => ['index']]);
        $this->middleware('permission:view details_work_orders_management', ['only' => ['show']]);
        $this->middleware('permission:create_work_orders_management', ['only' => ['create','store']]);
        $this->middleware('permission:update_work_orders_management', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_work_orders_management', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:update phase_work_orders_management', ['only' => ['updatePhase', 'indexPhases']]);
        $this->middleware('permission:remove_work_orders_management', ['only' => ['destroy', 'destroy_all']]);
        $this->middleware('permission:print_work_orders_management', ['only' => ['print']]);
        $this->middleware('permission:print statement_work_orders_management', ['only' => ['printStatement']]);
        $this->middleware('permission:log phases details_work_orders_management', ['only' => ['getLogPhases']]);
    }

    use WorkOrdersManagementTable;

    public function index()
    {
        $user = Auth::user();
        $userId = Auth::id();
        $branchesUserIds = $user->branches->pluck('id');

        $query = WorkOrdersManagement::orderByDesc("id");

        if ($user->hasRole('technician')) {
            $technician = Technicians::where('user_id', $userId)->first();

            if (!empty($technician)) {
                $technicianBranches = $technician->branches->pluck('id');

                $query->whereIn('branch_id', $technicianBranches);
            }
        } else {
            if (!empty($branchesUserIds) && !$user->hasRole('owner')) {
                $query->whereIn("branch_id", $branchesUserIds);
            }
        }

        $workOrders = $query->limit(50)->get();
        return view('work_orders.work_orders_management.index', compact('workOrders'));
    }

    public function show(int $id)
    {
        $workOrder = WorkOrdersManagement::with('branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy')->where('id', $id)->first();
        $logWorkOrder = WorkOrdersLog::with('workOrders', 'users', 'phases')->where("work_order_id", $id)->get();

        return view('work_orders.work_orders_management.show', [
            'workOrder' => $workOrder,
            'logWorkOrder' => $logWorkOrder,
        ]);
    }

    public function reader()
    {
        return view('work_orders.delivering_work_orders.index');
    }

    public function readerUpdatePhase(Request $request)
    {
        $barcode = $request['barcode_code'];
        $workOrder = WorkOrdersManagement::where('work_order_number', $barcode)->first();
        if (!empty($workOrder)) {
            $workOrder["phase_id"] = 16;
            $workOrder->save();

            registrationWorkOrderLog($workOrder["id"], $workOrder["phase_id"]);

            $deliveries = getDeliveryUserByBranch($workOrder["branch_id"]);

            if ((int)$request["phase_id"] === 6) {
                foreach ($deliveries as $delivery) {
                    $this->sendNotificationToDelivery($delivery, $workOrder, "message_back_to_rehearsal_phase_for_delivery");
                }
            } elseif ((int)$request["phase_id"] === 15) {
                $this->sendNotificationToCommissioner($workOrder);
                $this->sendWorkOrderNotification($workOrder, "work_order_dlivery_message");
                foreach ($deliveries as $delivery) {
                    $this->sendNotificationToDelivery($delivery, $workOrder, "message_back_to_rehearsal_phase_for_delivery");
                }
            }

            return response()->json(['success' => __('Work order was successfully delivered')]);
        } else {
            return response()->json(['error' => __('No work order found')]);
        }
    }

    public function create()
    {
        return view('work_orders.work_orders_management.create');
    }

    public function store(WorkOrdersManagementRequest $request)
    {
        $lock = Cache::lock('WorkOrdersManagementLock', LOOK_COUNT);
        if ($lock->get()) {
            try {
                $today = Carbon::today();
                $service = DoctorsServicesDetails::where('service_id', $request->service_id)->first();
                $doctor = Doctors::find($request->doctor_id);
                $client = Clients::find($doctor->client_id);
                $offer = !empty($doctor->client_id) ? SpecialOffers::where('doctor_id', $doctor->id)
                    ->whereBetween('start_date', [$today, $today])
                    ->first() : null;

                if (isset($request->request_id)) {
                    $doctorRequest = DoctorsRequest::find($request->request_id);
                    if (!empty($doctorRequest)) {
                        if (!empty($doctorRequest->confirmed_by)) {
                            return response()->json(['error' => __('Doctor request already confirmed')]);
                        }
                    }
                }
                $count = $request->service_count;
                if (auth()->user()->can('service details_work_orders_management')) {
                    $price = $request->service_price;
                    $discount = $request->service_discount;
                } else {
                    $price = $service->price;
                    $discount = !empty($offer) ? max($offer->discount, $client->discount) : ($client->discount ?? $service->service_discount);
                }

                $discountValue = ($discount * $price) / 100;
                $net = ($price * $count) - ($discountValue * $count);
                $tax = $request->service_tax ?? $service->service_tax;
                $taxVal = ($tax * $net) / 100;
                $total = $net + $taxVal;

                $workOrderDate = auth()->user()->can('change date_work_orders_management') ? $request->work_order_date : Carbon::now()->format('Y-m-d');

                $newWorkOrder = WorkOrdersManagement::create([
                    'work_order_number' => $request->work_order_number,
                    'work_order_date' => $workOrderDate,
                    'branch_id' => $request->branch_id,
                    'doctor_id' => $request->doctor_id,
                    'client_id' => $doctor->client_id,
                    'color_id' => $request->color_id,
                    'service_id' => $request->service_id,
                    'service_count' => $count,
                    'service_price' => (float)$price,
                    'services_price' => (float)$price * $count,
                    'service_discount' => (float)$discount,
                    'service_discount_value' => (float)$discountValue,
                    'services_discount_value' => (float)$discountValue * $count,
                    'service_total_net' => (float)$net,
                    'service_tax_value' => (float)$taxVal,
                    'service_tax' => (float)$tax,
                    'service_total' => (float)$total,
                    'patient_name' => $request->patient_name,
                    'statement' => $request->statement,
                    'file_number' => $request->file_number,
                    'phase_id' => 1,
                    'created_by' => Auth::id(),
                    'note' => $request->note,
                ]);

                if (isset($request->request_id)) {
                    $doctorRequest = DoctorsRequest::find($request->request_id);
                    $doctorRequest->update([
                        "confirmed_by" => Auth::id(),
                        "confirmed_at" => now(),
                    ]);
                }

                registrationWorkOrderLog($newWorkOrder->id, 1);
                $this->sendNotifications($newWorkOrder);

                return response()->json(['success' => __('Work order created successfully')]);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            } finally {
                $lock->release();
            }
        } else {
            return response()->json(['error' => __('There is another process currently performing this operation, please wait')], 423);
        }
    }

    private function sendNotifications($workOrder)
    {
        $doctor = Doctors::find($workOrder->doctor_id);
        $phone = "966".ltrim($doctor->mobile, '0');

        if (settings("new_work_order_message") != null) {
            $BodyContent = settings("new_work_order_message");
            $replacements = [
                '{user}' => auth()->user()->name,
                '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                '{work_order_number}' => $workOrder->work_order_number,
                '{patient_name}' => $workOrder->patient_name,
            ];
            $body = str_replace(array_keys($replacements), array_values($replacements), $BodyContent);
        } else {
            $body = "تم استلام حالتكم رقم ".$workOrder->id." للمريض ".$workOrder->patient_name." وسيتم ابلاغكم وقت انهائها. يمكنكم متابعة الحالة من خلال الرابط: https://alanan-stl.com/landing/work_orders/search_page";
        }

        sendWhatsappMessage($body, $phone, WorkOrdersManagement::class, [
            'work_order_id' => $workOrder?->id,
            'phase_id'      => 1,
        ]);
        if (settings("sms_check") == 1) { SMSMessage($body, $phone); }
    }

    public function edit(string $id)
    {
        $workOrder = WorkOrdersManagement::find($id);
        $User = Auth::user();
        if ($User->hasRole('technician')) {
            $technicianId = Technicians::where('status', '=', 'true')->where("user_id", Auth::id())->value('id');
            $branchIds = DB::table("many_branches_technicians")->where('technician_id', '=', $technicianId)->pluck('branch_id');
            $branches = DB::table("branches")->where('status', '=', 'true')
               ->whereIn("id", $branchIds)
               ->select(['id','name_ar', 'name_en'])
               ->orderby('id','asc')
               ->distinct()
               ->get();
        } elseif ($User->hasRole(['owner','admin'])) {
            $branches = Branch::where('status', '=', 'true')
               ->select(['id','name_ar', 'name_en'])
               ->orderby('id','asc')
               ->distinct()
               ->get();
        } else {
            $branches = Branch::where('status', '=', 'true')
               ->select(['id','name_ar', 'name_en'])
               ->orderby('id','asc')
               ->distinct()
               ->get();
        }
        $madeInvoiceForIt = !empty(DB::table("many_work_orders_invoices")->where('work_order_id', '=', $id)->first())? 1 : 0;
        $services = Services::select (['id', 'name_ar', 'name_en'])->get();
        $doctors = Doctors::select (['id', 'name_ar', 'name_en'])->get();
        $colors = ColorCodes::select(['id', 'color_code'])->get();

        return view('work_orders.work_orders_management.edit', [
            'workOrder' => $workOrder,
            'branches' => $branches,
            'services' => $services,
            'doctors' => $doctors,
            'colors' => $colors,
            'madeInvoiceForIt' => $madeInvoiceForIt,
        ]);
    }

    public function update(WorkOrdersManagementUpdateRequest $request, int $id)
    {
        try {
            $workOrder = WorkOrdersManagement::find($id);
            $madeInvoiceForIt = DB::table("many_work_orders_invoices")
                ->where('work_order_id', $id)
                ->exists();

            if (!$madeInvoiceForIt) {
                $today = Carbon::today();
                $service = DoctorsServicesDetails::where('service_id', $request->service_id)->first();
                $doctor = Doctors::find($request->doctor_id);
                $client = Clients::find($doctor->client_id);
                $offer = !empty($doctor->client_id) ? SpecialOffers::where('doctor_id', $doctor->id)
                    ->whereBetween('start_date', [$today, $today])
                    ->first() : null;

                $count = $request->service_count;
                if (auth()->user()->can('service details_work_orders_management')) {
                    $price = $request->service_price;
                    $discount = $request->service_discount;
                } else {
                    $price = $service->price;
                    $discount = !empty($offer) ? max($offer->discount, $client->discount) : ($client->discount ?? $service->service_discount);
                }

                $discountValue = ($discount * $price) / 100;
                $net = ($price * $count) - ($discountValue * $count);
                $tax = $request->service_tax ?? $service->service_tax;
                $taxVal = ($tax * $net) / 100;
                $total = $net + $taxVal;

                $workOrderDate = auth()->user()->can('change date_work_orders_management') ? $request->work_order_date : Carbon::now()->format('Y-m-d');

                $workOrder->update([
                    'work_order_number' => $request->work_order_number,
                    'work_order_date' => $workOrderDate,
                    'branch_id' => $request->branch_id,
                    'doctor_id' => $request->doctor_id,
                    'client_id' => $doctor->client_id,
                    'color_id' => $request->color_id,
                    'service_id' => $request->service_id,
                    'service_count' => $count,
                    'service_price' => (float)$price,
                    'services_price' => (float)$price * $count,
                    'service_discount' => (float)$discount,
                    'service_discount_value' => (float)$discountValue,
                    'services_discount_value' => (float)$discountValue * $count,
                    'service_total_net' => (float)$net,
                    'service_tax_value' => (float)$taxVal,
                    'service_tax' => (float)$tax,
                    'service_total' => (float)$total,
                    'patient_name' => $request->patient_name,
                    'statement' => $request->statement,
                    'file_number' => $request->file_number,
                    'phase_id' => 1,
                    'updated_by' => Auth::id(),
                    'note' => $request->note,
                ]);
            } else {
                $workOrder->update([
                    'work_order_date' => $request->work_order_date,
                    'updated_by' => Auth::id(),
                ]);
            }

            return response()->json(['success' => __('Work order updated successfully')]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getLogPhases(string $id)
    {
        $logs = WorkOrdersLog::with("phases", "users")
           ->where('work_order_id', $id)
           ->orderby('id','asc')
           ->distinct()
           ->get();
        $response = array();
        foreach($logs as $index => $item) {
            $response[] = array(
                'id' => $index + 1,
                'phase' => (app()->getLocale() == "ar") ? $item->phases->name_ar : $item->phases->name_en,
                'date' => getDateDMY($item->log_date),
                'time' => getTime($item->log_time),
                'user' => $item->users->name,
            );
        }

        return response()->json($response);
    }

    public function print(string $id)
    {
        $workOrder = WorkOrdersManagement::with('branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy')->where("id", $id)->first();
        $data = $workOrder->work_order_number;
        $barcode = new DNS1D();
        $barcodeData = $barcode->getBarcodeJPG($data, 'C39');

        return view("work_orders.work_orders_management.print", [
            "workOrder" => $workOrder,
            'barcodeData' => $barcodeData
        ]);
    }

    public function printStatement(string $id)
    {
        $workOrder = WorkOrdersManagement::with('branches', 'doctors', 'clients', 'colors', 'services', 'phases', 'createdBy', 'updatedBy')->where('id', $id)->first();
        $logWorkOrder = WorkOrdersLog::with('workOrders', 'users', 'phases')->where("work_order_id", $id)->get();

        return view('work_orders.work_orders_management.print_statement', [
            'workOrder' => $workOrder,
            'logWorkOrder' => $logWorkOrder,
        ]);
    }

    public function updatePhase(Request $request)
    {
        try {
            $workOrder = WorkOrdersManagement::with("phases")->find($request["id"]);
            $workOrder["phase_id"] = $request["phase_id"];
            $workOrder->save();

            registrationWorkOrderLog($workOrder["id"], $workOrder["phase_id"]);

            $deliveries = getDeliveryUserByBranch($workOrder["branch_id"]);

            if ((int)$request["phase_id"] === 6) {
                foreach ($deliveries as $delivery) {
                    $this->sendNotificationToDelivery($delivery, $workOrder, "message_back_to_rehearsal_phase_for_delivery");
                }
            } elseif ((int)$request["phase_id"] === 15) {
                $this->sendNotificationToCommissioner($workOrder);
                $this->sendWorkOrderNotification($workOrder, "work_order_dlivery_message");
                foreach ($deliveries as $delivery) {
                    $this->sendNotificationToDelivery($delivery, $workOrder, "message_back_to_rehearsal_phase_for_delivery");
                }
            }

            return response()->json(['success' => __('Phase updated successfully')]);
        } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status(Request $request, $id)
    {
        try {
            $data = WorkOrdersManagement::find($id);
            $data->status = $request->status;
            $data->save();

            return response()->json(['success' => __('Status updated successfully')]);
        } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status_all(Request $request)
    {
        try {
            $identities = $request->get('id');
            $status = $request->get('status');
            WorkOrdersManagement::whereIn('id', $identities)->update(['status' => $status]);

            return response()->json(['success' => __('Selected records status updated successfully')]);
        } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id)
    {
        try {
            $data = WorkOrdersManagement::find($id);
            $data->delete();

            return response()->json(['success' => __('Work order deleted successfully')]);
        } catch (\Exception $e) {
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
            WorkOrdersManagement::whereIn('id', $identities)->delete();

            return response()->json(['success' => __('Selected records deleted successfully')]);
        } catch (\Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }

    public function indexPhases()
    {
        return view('work_orders.update_phases.index');
    }

    public function searchPage()
    {
        return view('landing.work_orders.search');
    }

    use LogWorkOrdersTable;
    use LogPhasesWorkOrdersTable;

    public function searchWorkOrder(Request $request)
    {
        $user = Auth::user();
        $userId = Auth::id();
        $number = $request["number"];

        if ($user->hasRole('technician')) {
            $technicianId = Technicians::where('user_id', $userId)->value('id');
            $phaseIds = DB::table('many_phases_technicians')
                ->where('technician_id', $technicianId)
                ->pluck('phase_id');
            $workOrder = WorkOrdersManagement::where('work_order_number', $number)->orwhere('file_number', $number)->first();
            if (!empty($phaseIds)) {
                $workingPhases = WorkingPhases::where('status', 'true')->whereIn('id', $phaseIds)->get();
            } else {
                $workingPhases = [];
            }
        } else {
            $workOrder = WorkOrdersManagement::where('work_order_number', $number)->orwhere('file_number', $number)->first();
            $workingPhases = WorkingPhases::where('status', 'true')->get();
        }


        return view('work_orders.update_phases.index', compact('workingPhases', 'workOrder', 'number'));
    }

    public function updatePhaseAfterSearch(Request $request)
    {
        try {
            $number = $request["number"];

            $workOrder = WorkOrdersManagement::with("phases")->find($request["id"]);
            $workOrder["phase_id"] = $request["phase_id"];
            $workOrder->save();

            registrationWorkOrderLog($workOrder["id"], $workOrder["phase_id"]);
            $deliveries = getDeliveryUserByBranch($workOrder["branch_id"]);

            if ((int)$request["phase_id"] === 6) {
                foreach ($deliveries as $delivery) {
                    $this->sendNotificationToDelivery($delivery, $workOrder, "message_back_to_rehearsal_phase_for_delivery");
                }
            } elseif ((int)$request["phase_id"] === 15) {
                $this->sendNotificationToCommissioner($workOrder);
                $this->sendWorkOrderNotification($workOrder, "work_order_dlivery_message");
                foreach ($deliveries as $delivery) {
                    $this->sendNotificationToDelivery($delivery, $workOrder, "message_back_to_rehearsal_phase_for_delivery");
                }
            }

            return redirect()->back()->with('success', __('Phase updated successfully'))->with('number', $number);
        } catch (\Exception $e) { return response()->json(['error' => $e->getMessage()], 500); }
    }

    private function sendWorkOrderNotification($workOrder, $messageSettingKey)
    {
        $doctor = Doctors::find($workOrder["doctor_id"]);
//        $client = Clients::find($doctor["client_id"]);
        $phone = formatPhoneNumber("966", $doctor["mobile"]);
        $body = generateMessageContent($messageSettingKey, [
            '{user}' => auth()->user()->name,
            '{center_name}' => getCenterName(),
            '{work_order_number}' => $workOrder["work_order_number"],
            '{patient_name}' => $workOrder["patient_name"],
            '{physician}' => getLocaleName($doctor, "name_ar", "name_en"),
        ], "تم تجهيز حاله رقم {$workOrder["work_order_number"]} للمريض {$workOrder["patient_name"]} وسيتم ارسالها لكم مباشرة");

        sendWhatsappMessage($body, $phone, WorkOrdersManagement::class);
        if (settings("sms_check") == 1) { SMSMessage($body, $phone); }
    }

    private function sendNotificationToDelivery($delivery, $workOrder, $messageSettingKey)
    {
        $doctor = Doctors::find($workOrder["doctor_id"]);
        $phone = formatPhoneNumber($delivery["country_code"], $delivery["mobile"]);
        $body = generateMessageContent($messageSettingKey, [
            '{user}' => auth()->user()->name,
            '{center_name}' => getCenterName(),
            '{work_order_number}' => $workOrder["id"],
            '{patient_name}' => $workOrder["patient_name"],
            '{physician}' => getLocaleName($doctor, "name_ar", "name_en"),
        ]);

        sendWhatsappMessage($body, $phone, WorkOrdersManagement::class);
        if (settings("sms_check") == 1) { SMSMessage($body, $phone); }
    }
    private function sendNotificationToCommissioner($workOrder)
    {
        $clientId = $workOrder["client_id"];
        $commissionerNumber = Clients::find($clientId)->commissioner_number;
        if (!empty($commissionerNumber)) {
            $doctor = Doctors::find($workOrder["doctor_id"]);
            $phone = formatPhoneNumber("966", $commissionerNumber);
            $body = generateMessageContent('message_final_exit_phase_for_delivery', [
                '{user}' => auth()->user()->name,
                '{center_name}' => getCenterName(),
                '{work_order_number}' => $workOrder["work_order_number"],
                '{patient_name}' => $workOrder["patient_name"],
                '{physician}' => getLocaleName($doctor, "name_ar", "name_en"),
            ], "تم تجهيز حاله رقم {$workOrder["work_order_number"]} للمريض {$workOrder["patient_name"]} وسيتم ارسالها لكم مباشرة");

            sendWhatsappMessage($body, $phone, WorkOrdersManagement::class);
            if (settings("sms_check") == 1) { SMSMessage($body, $phone); }
        }
    }

}


