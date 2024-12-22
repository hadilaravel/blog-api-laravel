<?php

namespace Modules\Access\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Access\Entities\Permission;
use Modules\Access\Entities\Role;
use Modules\Access\Http\Requests\PermissionRequest;
use Modules\Access\Http\Requests\RoleRequest;

class AccessController extends Controller
{

    public function index()
    {
        $roles = Role::query()->whereNot('name' ,'super admin')->get();
        return response()->json([
            'roles' => $roles
        ]);
    }


    public function store(RoleRequest $request)
    {
        $inputs = [
            'name' => $request->name,
            'description' => $request->description,
        ];
      $role = Role::query()->create($inputs);
     if($role)
     {
         return response()->json([
             'msg' => 'عملیات با موفقیت انجام شد'
         ]);
     }else{
         return response()->json([
             'msg' => 'عملیات با خطا مواجه شد!!'
         ]);
     }
    }


    public function update(RoleRequest $request, Role $role)
    {
        $inputs = [
            'name' => $request->name,
            'description' => $request->description,
        ];
        $role->update($inputs);
        return response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }



    public function permissionShow(Role $role)
    {
        $permissions = Permission::all();
        return response()->json([
            'permissions' => $permissions,
            'role' => $role
        ]);
    }

    public function permissionStore(PermissionRequest $request , Role $role)
    {
        $inputs = $request->all();
        $inputs['permissions'] = $inputs['permissions'] ?? [];
        $role->permissions()->sync($inputs['permissions']);
        return response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

    public function permissionDelete(Role $role , Permission $permission)
    {
        $role->permissions()->detach($permission->id);
        return response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

}
