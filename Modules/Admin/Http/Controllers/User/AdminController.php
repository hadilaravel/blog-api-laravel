<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Models\User;
use App\Service\ShareService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Modules\Access\Entities\Role;
use Modules\Admin\Http\Requests\User\UserAdminRequest;
use Modules\Admin\Transformers\User\UserAdminCollection;
use Modules\Admin\Transformers\User\UserAdminResource;

class AdminController extends Controller
{

    public function index(Request $request)
    {
        $userAdminsCount = User::query()->where('user_type' , 1)->get();
        if(isset($request->search) || isset($request->take) ){
            $search = $request->search;
            $take = $request->take ?? 8;
            $userAdmins = User::query()->where('user_type' , 1)->where('name' , 'LIKE', '%' . $search . '%' )->paginate($take);
        }else {
            $search ='';
            $userAdmins = User::query()->where('user_type' , 1)->paginate(8);
        }
        return \response()->json([
            'userAdmins' => new UserAdminCollection($userAdmins),
            'entityCount' => $userAdminsCount->count(),
            'PageCount' => (int) ceil($userAdmins->total() / $userAdmins->perPage())
        ]);
    }

    public function singleUserAdmin(User $user)
    {
        return response()->json([
           'userAdmin' => new UserAdminResource($user)
        ]);
    }

    public function indexAdmin()
    {
        $user = auth()->user();
        return response()->json([
           'admin ' => new UserAdminResource($user)
        ]);
    }

    public function store(UserAdminRequest $request)
    {
        $inputs = [
            'name' => $request->name,
            'username' => $request->username ,
            'password' =>  Hash::make($request->password),
            'activation' => $request->activation,
            'user_type'=> 1,
        ];
        if($request->hasFile('profile')) {
            $imageName = ShareService::uploadFilePublic($request->file('profile') ,'profile/user-admin');
            $inputs['profile'] = $imageName;
        }
        $user = User::query()->create($inputs);
        if($user)
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


    public function update(UserAdminRequest $request, User $user)
    {
        $inputs = [
            'username' => $request->username ,
            'name' => $request->name,
            'password' =>  Hash::make($request->password),
            'activation' => $request->activation,
            'user_type'=> 1,
        ];
        if($request->hasFile('profile')) {
            ShareService::deleteFilePublic($user->profile);
            $imageName = ShareService::uploadFilePublic($request->file('profile') ,'profile/user-admin');
            $inputs['profile'] = $imageName;
        }
        $user->update($inputs);
        return response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

    public function destroy(User $user)
    {
        ShareService::deleteFilePublic($user->profile);
        $user->delete();
        return response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

    public function activation(User $user)
    {
        $user->activation = $user->activation == 0 ? 1 : 0;
        $user->save();
        return response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }


    public function roleStore(Request $request , User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,id',
        ]);
        $user->roles()->sync($request->role);
        return response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

    public function roleDelete(User $user , Role $role)
    {
        $user->roles()->detach($role->id);
        return response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

}
