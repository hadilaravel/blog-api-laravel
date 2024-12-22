<?php

namespace Modules\Admin\Http\Controllers\Setting;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Setting\SmsSetting;
use Modules\Admin\Http\Requests\Setting\SmsSettingRequest;
use function view;

class SmsSettingController extends Controller
{

    public function index()
    {
        $smsSetting = SmsSetting::query()->first();
        return response()->json([
            'smsSetting' => $smsSetting
        ]);
    }


    public function update(SmsSettingRequest $request, SmsSetting $smsSetting)
    {
        $inputs = [
            'username' => $request->username,
            'password' => $request->password,
            'from' => $request->from,
        ];
        $smsSetting->update($inputs);
        return response()->json([
            'msg' => 'عملیات با موفقیت انجام شد'
        ]);
    }

}
