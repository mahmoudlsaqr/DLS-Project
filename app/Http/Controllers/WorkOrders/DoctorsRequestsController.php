<?php

namespace App\Http\Controllers\WorkOrders;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorsRequestsRequest;
use App\Http\Traits\TablesBuilderTraits\WorkOrders\DoctorsRequestsTable;
use App\Models\DoctorsRequest;
use App\Models\DoctorsRequestsArchive;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DoctorsRequestsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_doctors_requests', ['only' => ['index', 'data']]);
        $this->middleware('permission:confirm_doctors_requests', ['only' => ['confirm']]);
    }

    use DoctorsRequestsTable;

    public  function index()
    {
        return view('work_orders.doctors-requests.index');
    }

    public  function create()
    {
        return view('landing.doctors-requests.create');
    }

    public  function store(DoctorsRequestsRequest $request)
    {
        $lock = Cache::lock('DoctorsRequestsLock', LOOK_COUNT);

        try {
            if (!$lock->get()) {
                return response()->json([
                    'error' => __('Another process is currently handling this operation. Please wait and try again shortly.')
                ]);
            }

            DB::beginTransaction();

            $userId = Auth::id();
            $data = Arr::except($request->all(), ['_token', '_method']);

            // Begin - Create Doctor Request Code
            $prefix = "R";
            $year = Carbon::now()->year;
            $shortYear = $year % 100;
            $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfDay();
            $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfDay();

            $lastDoctorsRequest = DoctorsRequest::whereBetween('created_at', [$startOfYear, $endOfYear])
                ->select('main_code')
                ->latest('id')
                ->first();

            $sequence = 1;
            if ($lastDoctorsRequest) {
                $lastCode = Str::afterLast($lastDoctorsRequest->main_code, $shortYear);
                $lastSequence = (int) $lastCode;

                $sequence = $lastSequence + 1;
            }
            $doctorsRequestCode = sprintf('%s%02d%04d', $prefix, $shortYear, $sequence);
            // End - Create Doctor Request Code

            $data['main_code'] = $doctorsRequestCode;

            $item = DoctorsRequest::create($data);

            if ($request->hasFile('attachments')) {
                $attachments = (array) $request->file('attachments');
                $allowedExtensions = ["ply", "stl", "obj"];

                foreach ($attachments as $attachment) {
                    if ($attachment->isValid()) {
                        $extension = strtolower($attachment->getClientOriginalExtension());
                        $sizeInMB = round($attachment->getSize() / (1024 * 1024), 2);

                        if (in_array($extension, $allowedExtensions)) {
                            $imageName = uniqid() . "_$item->id." . $extension;

                            $stored = $attachment->storeAs('attachments-doctors-requests', $imageName, 'uploads');
                            if ($stored) {
                                DoctorsRequestsArchive::create([
                                    'request_id' => $item->id,
                                    'filename' => $imageName,
                                    'size' => $sizeInMB,
                                    'created_by' => $userId,
                                ]);
                            }
                        }
                    }
                }
            }

            $mobiles = [
                settings('center_admin_mobile') ?? null,
                settings('additional_mobile_1') ?? null,
                settings('additional_mobile_2') ?? null,
                settings('additional_mobile_3') ?? null,
                settings('additional_mobile_4') ?? null
            ];

            foreach ($mobiles as $mobile) {
                if (!empty($mobile)) {
                    $body = "تم إضافة طلب جديد برقم " . $item->id . " للمريض " . $item->patient_name . " وسيتم ابلاغكم وقت انهائها. يمكنكم متابعة الحالة من خلال الرابط: https://alanan-stl.com/work_orders/doctors-requests";

                    sendWhatsappMessage($body, $mobile, DoctorsRequest::class);
                    if (settings("sms_check") == 1) { SMSMessage($body, $mobile); }
                }
            }

            DB::commit();

            return response()->json(["success" => __("Request has been added successfully."),]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        } finally {
            optional($lock)->release();
        }
    }

    public  function confirm(string $id)
    {
        $id = decrypt($id);
        $request = DoctorsRequest::find($id);

        $item = [
            'id' => $request->id,
            'work_order_number' =>  $request->main_code,
            'color_id' =>  $request->color_code,
            'service_count' => $request->number_teeth,
            'patient_name' => $request->patient_name,
            'file_number' => $request->file_number,
        ];

        return view('work_orders.doctors-requests.confirm', [
            'item' => $item,
        ]);
    }

    public function getDetails(int $id)
    {

        try {
            $item = DoctorsRequest::find($id);
            $response = array();
            foreach ($item->attachments as $attachment) {
                $response[] = [
                    'id'       => $attachment->id,
                    'filename' => $attachment->filename ?? null,
                    'size'     => $attachment->size . ' MB',
                ];
            }

            return response()->json($response);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function download(Request $request)
    {
        try {
            $id = $request->input('id');

            if (empty($id)) {
                return response()->json(['error' => __('File not found')], 404);
            }

            $item = DoctorsRequestsArchive::find($id);

            if (!$item || empty($item->filename)) {
                return response()->json(['error' => __('File not found')], 404);
            }

            $filename = basename($item->filename);
            $path = public_path("uploads/attachments-doctors-requests/{$filename}");

            if (!file_exists($path)) {
                return response()->json(['error' => __('File not found')], 404);
            }

            // لو بس عايز تتأكد بدون ما تحمّل
            if ($request->has('check_only')) {
                return response()->json(['valid' => true]);
            }

            return response()->download($path);

        } catch (Exception $e) {
            return response()->json(['error' => __('Something went wrong')], 500);
        }
    }


}
