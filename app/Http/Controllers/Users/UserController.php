<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Http\Traits\TablesBuilderTraits\Users\UsersTable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Exception;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_users', ['only' => ['index']]);
        $this->middleware('permission:create_users', ['only' => ['create','store']]);
        $this->middleware('permission:update_users', ['only' => ['update','edit']]);
        $this->middleware('permission:update status_users', ['only' => ['update_status', 'update_status_all']]);
        $this->middleware('permission:remove_users', ['only' => ['destroy', 'destroy_all']]);
    }

    use UsersTable;
    public function store(UserRequest $request)
    {
        $lock = Cache::lock('usersLock', LOOK_COUNT);
        if ($lock ->get()) {
            try {

                if ($request['role'] == "physician") {
                    $request->validate([
                        "supervising" => "required"
                    ]);
                }

                $input = User::create([
                    'name' => $request['name'],
                    'username' => $request['username'],
                    'email' => $request['email'],
                    'role' => $request['role'],
                    'status' => 'true',
                    'supervising' => $request['supervising'],
                    'country_code' => $request['country_code'],
                    'mobile' => $request['mobile'],
                    'receiving_orders' => $request['receiving_orders'],
                    'skipping' => $request['skipping'],
                    'created_by' => Auth::id(),
                    'password' => bcrypt($request['password']),
                ]) ->assignRole($request ->input('role'));

                $key = $input ->getKey();
                if (!empty($request["avatar"])) {
                    createAvatar($request, 'avatar', $key);
                }

                if (!empty($request['branches'])) {
                    $branches = $request['branches'];
                    $input ->branches() ->sync($branches);
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
                    'user_id'       => $key,
                ]);

                if (settings("sms_check") == 1) { SMSMessage($body, $phone); }

                return response() ->json(['success' => __('User created successfully')]);
            } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); } finally { $lock ->release(); }
        } else { return response() ->json(['error' => __('There is another process currently performing this operation, please wait')], 423); }
    }

    public function edit($id)
    {
        try {
            $user = User::find($id);
            $branches = DB::table('many_users_branches')
                ->where('user_id', $user->id)
                ->pluck('branch_id')
                ->toArray();

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'country_code' => $user->country_code,
                'mobile' => $user->mobile,
                'role' => $user->role,
                'supervising' => $user->supervising,
                'receiving_orders' => $user->receiving_orders,
                'skipping' => $user->skipping,
                'branches' => array_values($branches),
            ];
            return response() ->json($data);

        } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update(UserUpdateRequest $request)
    {
        try {
            $user = User::find($request["id"]);
            $user ->update([
                'name' => $request["name"],
                'username' => $request["username"],
                'email' => $request["email"],
                'role' => $request["role"],
                'supervising' => $request['supervising'],
                'country_code' => $request['country_code'],
                'mobile' => $request['mobile'],
                'receiving_orders' => $request['receiving_orders'],
                'skipping' => $request['skipping'],
                'updated_by' => Auth::id(),
                'password' => $request['password'] == null ? $user ->password : bcrypt($request['password']),
            ]);

            DB::table('model_has_roles') ->where('model_id', $user["id"]) ->delete();
            $user ->assignRole($request ->input('role'));

            if (!empty($request['branches'])) {
                $branches = $request['branches'];
                $user ->branches() ->sync($branches);
            }

            updateAvatar($request,'avatar', $user["avatar"], $user["id"]);

            return response() ->json(['success' => __('User updated successfully')]);
        } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status(Request $request, $id)
    {
        try {
            $data = User::find($id);
            $data ->status = $request ->status;
            $data ->save();

            return response() ->json(['success' => __('Status updated successfully')]);
        } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function update_status_all(Request $request)
    {
        try {
            $identities = $request ->get('id');
            $status = $request ->get('status');
            User::whereIn('id', $identities) ->update(['status' => $status]);

            return response() ->json(['success' => __('Selected records status updated successfully')]);
        } catch (Exception $e) { return response() ->json(['error' => $e->getMessage()], 500); }
    }

    public function destroy($id)
    {
        try {
            $data = User::find($id);

            if ($data->hasRole('owner')) {
                return response() ->json(['error' => __('Owner cannot be deleted')]);
            }
            $data ->delete();

            return response() ->json(['success' => __('User deleted successfully')]);
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
                $data = User::find($id);
                if ($data->hasRole('owner')) {
                    return response() ->json(['error' => __('Owner cannot be deleted')]);
                }
            }
            User::whereIn('id', $identities) ->delete();

            return response() ->json(['success' => __('Selected records deleted successfully')]);
        } catch (Exception $e) {
            $message = str_contains($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: 1451")
                ? __("Cannot delete or update a parent row: a foreign key constraint failed")
                : $e->getMessage();
            return response()->json(['error' => $message], str_contains($e->getMessage(), "SQLSTATE[23000]") ? 422 : 500);
        }
    }
}
