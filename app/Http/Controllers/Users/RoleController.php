<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_roles', ['only' => ['index']]);
        $this->middleware('permission:create_roles', ['only' => ['create','store']]);
        $this->middleware('permission:update_roles', ['only' => ['update','edit']]);
        $this->middleware('permission:remove_roles', ['only' => ['destroy']]);
    }

    public function index()
    {
        try {
            $roles = Role::get();
            return view('users.roles.index', ['roles' => $roles]);
        } catch (\Exception $e) { return redirect()->back()->with('error', $e->getMessage()); }
    }

    public function create()
    {
        try {
            $permissions = Permission::get();

            return view('users.roles.create',[
                'Permissions' => $permissions,
            ]);
        } catch (\Exception $e) { return redirect()->back()->with('error', $e->getMessage()); }
    }

    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|unique:roles,name',
                'permission' => 'required',
            ]);

            $role = Role::create(['name' => $request->get('name')]);
            $role->syncPermissions($request->get('permission'));

            return redirect('roles')->with('success', __('Role created successfully'));
        } catch (\Exception $e) { return redirect()->back()->with('error', $e->getMessage()); }
    }

    public function edit(int $id)
    {
        try {
            $role = Role::findOrFail($id);
            $permissions = Permission::get();
            $rolePermissions = DB::table('role_has_permissions')
                ->where('role_has_permissions.role_id', $id)
                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                ->all();
            return view('users.roles.edit',[
                'role' => $role,
                'Permissions' => $permissions,
                'RolePermissions' => $rolePermissions
            ]);
        } catch (\Exception $e) { return redirect()->back()->with('error', $e->getMessage()); }
    }

    public function update(Request $request, int $id)
    {
        try {
            $role = Role::findOrFail($id);
            $this->validate($request, [
                'name' => 'required|unique:roles,name,'.$role->id,
                'permission' => 'required',
            ]);

            $role->update([ 'name' => $request->name ]);
            $role = Role::findOrFail($role->id);
            $role->syncPermissions($request->permission);

            return redirect('roles')->with('success', __('Role updated successfully'));
        } catch (\Exception $e) { return redirect()->back()->with('error', $e->getMessage()); }
    }

    public function destroy($roleId)
    {
        try {
            $role = Role::find($roleId);
            $role->delete();
            return redirect('roles')->with('success', __('Role deleted successfully'));
        } catch (\Exception $e) { return redirect()->back()->with('error', $e->getMessage()); }
    }
}
