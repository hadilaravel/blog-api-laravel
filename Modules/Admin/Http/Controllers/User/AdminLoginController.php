<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminLoginController extends Controller
{


    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|max:120|min:5|regex:/^[a-zA-Z0-9]+$/u',
            'password' =>  ['required', Password::min(7)->letters()->mixedCase()->numbers()->symbols()],
        ]);

        $user = User::query()->where('username' , $request->username)->where('user_type' , 1)->where('activation' , 1)->first();
        if(empty($user))
        {
            return response()->json([
                'msg' => 'اطلاعات وارد شده نامعتبر می باشد !'
            ] , 422);
        }
        if(Hash::check( $request->password , $user->password ))
        {
            $accessToken = $user->createToken($user->username)->plainTextToken;
            Auth::loginUsingId($user->id);
            return  response()->json([
                'user' => Auth::user(),
                'accessToken' => $accessToken
            ]);
        }else{
            return response()->json([
                'msg' => 'اطلاعات وارد شده نامعتبر می باشد !'
            ] , 422);
        }

    }


}
