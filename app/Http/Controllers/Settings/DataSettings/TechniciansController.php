<?php

namespace App\Http\Controllers\Settings\DataSettings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\DataSettings\TechniciansRequest;
use App\Http\Requests\Settings\DataSettings\TechniciansUpdateRequest;
use App\Http\Traits\TablesBuilderTraits\Settings\DataSettings\TechniciansTable;
use App\Models\Technicians;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Exception;

class TechniciansController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_technicians', ['only' => ['index']]);
        $this->middleware('permission:create_technicians', ['only' => ['create','store']]);
        $this->middleware('permission:update_technicians', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_technicians', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove_technicians', ['only' => ['destroy', 'destroy_all']]);
    }
    use TechniciansTable;

    public function store(TechniciansRequest $request)
    {
        $lock = Cache::lock('TechniciansLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {
                $data = Technicians::create( $request ->all() );
                $data ->update([ 'created_by' => Auth::id() ]);

                if (!empty($data['branches'])) {
                    $branches = $data['branches'];
                    $data ->branches() ->sync($branches);
                }

                if (!empty($data['phases'])) {
                    $phases = $data['phases'];
                    $data ->phases() ->sync($phases);
                }

                $validated = $request->validate([
                    'username' => 'required|unique:users,username',
                ]);

                $userData = User::create([
                    'name' => $request['name_ar'],
                    'username' => $validated['username'],
                    'email' => $request['email'],
                    'role' => 'technician',
                    'country_code' => $request["country_code"],
                    'mobile' => $request['mobile'],
                    'created_by' => Auth::id(),
                    'password' => bcrypt($request['password']),
                ]) ->assignRole('technician');

                $data ->update([ 'user_id' => $userData["id"] ]);

                if (!empty($data['branches'])) {
                    $branches = $data['branches'];
                    $userData ->branches() ->sync($branches);
                }

                $username = strtolower($request['username']);
                $Name = strtolower($request['name']);
                $role = strtolower($request['role']);

                if (settings("new_user_message") != null) {
                    $BodyContent = settings("new_user_message");
                    $replacements = [
                        '{role}' => $role,
                        '{user}' => $Name,
                        '{username}' => $username,
                        '{password}' => $request['password'],
                        '{center_name}' => app()->getLocale() === "ar"? settings('center_name_ar') : settings('center_name_en'),
                    ];
                    $body = str_replace(array_keys($replacements), array_values($replacements), $BodyContent);
                } else {
                    $body = $Name." نتشرف بإضافتك كمستخدم بصلاحية ".$role." في ".settings('center_name_ar')."رابط الدخول alanan-stl.com اسم المستخدم: ".$username." وكلمة المرور: ".$request['password'];
                }

                $phone = formatPhoneNumber($request["country_code"], $request["mobile"]);

                sendWhatsappMessage($body, $phone, User::class, [
                    'user_id' => $userData["id"],
                ]);
                if (settings("sms_check") == 1) { SMSMessage($body, $phone); }

                return response() ->json(['success' => __('Technician created successfully')]);
            } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit(int $id)
    {
        $data = Technicians::find($id);
        return response() ->json($data);
    }

    public function update(TechniciansUpdateRequest $request, string $id)
    {
        try {
            $data = Technicians::find($id);
            $data ->update($request ->all());
            $data ->update(['updated_by' => Auth::id()]);

            if (!empty($data['branches'])) {
                $branches = $data['branches'];
                $data ->branches() ->sync($branches);
            }

            if (!empty($data['phases'])) {
                $phases = $data['phases'];
                $data ->phases() ->sync($phases);
            }

            $userData = User::find($data["user_id"]);
            if (!empty($userData) && !empty($data['branches'])) {
                $branches = $data['branches'];
                $userData ->branches() ->sync($branches);
            }

            return response() ->json(['success' => __('Technician updated successfully')]);
        } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status(Request $request, $id)
    {
        try {
            $data = Technicians::find($id);
            if (!empty($request->status)) {
                $data ->status = $request ->status;
            }
            $data ->save();

            return response() ->json(['success' => __('Status updated successfully')]);
        } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status_all(Request $request)
    {
        try {
            $identities = $request ->get('id');
            $status = $request ->get('status');
            Technicians::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json(['success' => __('Selected records status updated successfully')]);
        } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy(int $id)
    {
        try {
            $data = Technicians::find($id);
            $data ->delete();

            return response() ->json(['success' => __('Technician deleted successfully')]);
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
            Technicians::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => __('Selected records deleted successfully')]);
        } catch (Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
