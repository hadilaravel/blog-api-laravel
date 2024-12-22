<?php

namespace Modules\Admin\Http\Controllers\User;

use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CustomerController extends Controller
{

    public function index()
    {
        $customers = User::query()->where('user_type' , 0)->get();
        return response()->json([
            'customers' => $customers
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

}
