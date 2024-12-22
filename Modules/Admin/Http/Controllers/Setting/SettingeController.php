<?php

namespace Modules\Admin\Http\Controllers\Setting;

use App\Service\ShareService;
use Illuminate\Routing\Controller;
use Modules\Admin\Entities\Setting\Settinge;
use Modules\Admin\Http\Requests\Setting\SettingeRequest;
use function response;

class SettingeController extends Controller
{

    public function index()
    {
        $setting = Settinge::query()->first();
        return response()->json([
            'setting' => $setting
        ]);
    }

    public function update(SettingeRequest  $request , Settinge $setting)
    {
        $inputs = $request->all();

        if($request->hasFile('icon')) {
            ShareService::deleteFilePublic($setting->icon);
            $imageName = ShareService::uploadFilePublic($request->file('icon') ,'image/setting');
            $inputs['icon'] = $imageName;
        }
        if($request->hasFile('logo_footer')) {
            ShareService::deleteFilePublic($setting->logo_footer);
            $imageName = ShareService::uploadFilePublic($request->file('logo_footer') ,'image/setting/logo-footer');
            $inputs['logo_footer'] = $imageName;
        }
        if($request->hasFile('logo_header')) {
            ShareService::deleteFilePublic($setting->logo_header);
            $imageName = ShareService::uploadFilePublic($request->file('logo_header') ,'image/setting/logo-header');
            $inputs['logo_header'] = $imageName;
        }

        $setting->update($inputs);
        return response()->json([
            'msg' => 'تنظیمات با موفقیت انجام شد'
        ]);
    }

}
