<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Service\Message\Email\EmailService;
use App\Http\Service\Message\MessageService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Melipayamak\MelipayamakApi;
use Modules\Admin\Entities\Setting\Settinge;
use Modules\Admin\Entities\Setting\SmsSetting;
use Modules\Auth\Entities\Otp;
use Modules\Auth\Http\Requests\RegisterRequest;


class RegisterController extends Controller
{

    public function storeRegister(RegisterRequest $request)
    {
        $inputs = $request->all();
        $setting = Settinge::query()->first();

        //check id is email or not
        if(filter_var($inputs['id'], FILTER_VALIDATE_EMAIL))
        {
            $type = 1; // 1 => email
            $user = User::query()->where('email', $inputs['id'])->first();
            if(!empty($user) && !empty($user->email_verified_at)){
                return response()->json([
                    'msg' => 'ایمیل وارد شده تکراری است ! !'
                ]);
            }

            $newUser =  User::query()->firstOrCreate([
                'email' => $inputs['id'],
                'email' => $inputs['id']
            ]);
        }

        //check id is mobile or not
        elseif(preg_match('/^(\+98|98|0)9\d{9}$/', $inputs['id'])){
            $type = 0; // 0 => mobile;

            // all mobile numbers are in on format 9** *** ***
            $inputs['id'] = ltrim($inputs['id'], '0');
            $inputs['id'] = substr($inputs['id'], 0, 2) === '98' ? substr($inputs['id'], 2) : $inputs['id'];
            $inputs['id'] = str_replace('+98', '', $inputs['id']);

            $user = User::query()->where('mobile', $inputs['id'])->first();
            if(!empty($user) && !empty($user->mobile_verified_at) ){
                return response()->json([
                    'msg' => 'شماره تلفن  وارد شده تکراری است ! !'
                ]);
            }
            $newUser =  User::query()->firstOrCreate([
                'mobile' => $inputs['id'],
                'mobile' => $inputs['id'],
            ]);
        }
        else{
            return response()->json([
                'msg' => 'شناسه ورودی نه شماره موبایل است نه ایمیل'
            ]);
        }

        //create otp code
        $otpCode = rand(111111, 999999);
        $token = Str::random(60);
        $otpInputs = [
            'token' => $token,
            'user_id' => $newUser->id,
            'otp_code' => $otpCode,
            'login_id' => $inputs['id'],
            'type' => $type,
        ];

        Otp::query()->create($otpInputs);

        //send sms or email
        $title = 'کد ورود';
        $body = ' کد ورود شما به سایت' . $setting->title . ' :' . $otpCode ;

        if($type == 0){
            $smsSetting = SmsSetting::query()->first();
            if(!empty($smsSetting)) {
                try {
                    $username = $smsSetting->username;
                    $password = $smsSetting->password;
                    $api = new MelipayamakApi($username, $password);
                    $sms = $api->sms();
                    $to = '0' . $newUser->mobile;
                    $from = $smsSetting->from;
                    $text =  $body;
                    $response = $sms->send($to, $from, $text);
                    $json = json_decode($response);
                } catch (\Exception $e) {
                    return response()->json([
                        'msg' => 'تنظیمات مربوط به ارسال پیامک درست انجام نشده است'
                    ]);
                }
            }
        }
        elseif($type === 1 )
        {
            $emailService = new EmailService();
            $details = [
                'title' => $title,
                'body' =>  $body,
            ];
            $emailService->setDetails($details);
            $emailService->setFrom('noreply@example.com', 'shop');
            $emailService->setSubject($title);
            $emailService->setTo($newUser->email);

            $messageService = new MessageService($emailService);
            $messageService->send();
        }

        return to_route('auth.register.confirm.view' , $token);
    }


    public function viewConfirmRegister($token)
    {
        $setting = Setting::query()->first();
        $otp = Otp::query()->where('token' , $token)->first();
        if(empty($otp))
        {
            return to_route('auth.register.view')->withErrors(['id' => 'آدرس وارد شده نامعتبر می باشد']);
        }
        $loginId = $otp->login_id;
        return view('auth::registerVerify' , compact('loginId' , 'setting' , 'token'));
    }

    public function storeConfirmRegister(Request $request  , $token)
    {
      $request->validate([
          'otp_code' => 'required|min:6|max:6'
      ]);
        $inputs = $request->all();
        $otp = Otp::query()->where('token' , $token)->where('used' , 0)->first();
        if(empty($otp)){
            return to_route('auth.register.view')->withErrors(['otp_code' => 'آدرس وارد شده نامعتبر است']);
        }

        if($otp->otp_code !== $inputs['otp_code']){
            return to_route('auth.register.confirm.view' , $token)->withErrors(['otp_code' => 'کد وارد شده نامعتبر است']);
        }

        $otp->update(['used' => 1]);
        $user = $otp->user()->first();
        if($otp->type == 0)
        {
            $user->update(['mobile_verified_at' => Carbon::now()]);
        }
        elseif($otp->type == 1)
        {
            $user->update(['email_verified_at' => Carbon::now()]);
        }
        Auth::loginUsingId($user->id);
        return  to_route('auth.register.information');
    }

    public function sendAgainCode($token)
    {
        $setting = Setting::query()->first();
        $otp = Otp::query()->where('token' , $token)->where('used' , 0)->first();
        if(empty($otp)){
            return to_route('auth.register.confirm.view' , $token)->withErrors(['otp_code' => 'آدرس وارد شده نامعتبر است']);
        }

        //send sms or email
        $title = 'کد ورود';
        $body = ' کد ورود شما به ' . $setting->title . ' :' . $otp->otp_code ;

        if($otp->type == 0){
            $smsSetting = SmsSetting::query()->first();
            if(!empty($smsSetting)) {
                try {
                    $username = $smsSetting->username;
                    $password = $smsSetting->password;
                    $api = new MelipayamakApi($username, $password);
                    $sms = $api->sms();
                    $to = '0' . $otp->user->mobile;
                    $from = $smsSetting->from;
                    $text =  $body;
                    $response = $sms->send($to, $from, $text);
                    $json = json_decode($response);
                } catch (\Exception $e) {
                    alert()->error('تنظیمات مربوط به ارسال پیامک انجام نشده است');
                    return back();
                }
            }
        }
        elseif($otp->type === 1 )
        {
            $settingEmail = SettingEmail::query()->first();
            $envService = new Env();
            $envService->set("MAIL_USERNAME", $settingEmail->name);
            $envService->set("MAIL_PASSWORD", $settingEmail->password);

            $emailService = new EmailService();
            $details = [
                'title' => $title,
                'body' =>  $body,
            ];
            $emailService->setDetails($details);
            $emailService->setFrom('noreply@example.com', 'shop');
            $emailService->setSubject($title);
            $emailService->setTo($otp->user->email);

            $messageService = new MessageService($emailService);
            $messageService->send();
        }

        return to_route('auth.register.confirm.view' , $token)->with('success' , 'ارسال دوباره کد با موفقیت انجام شد');

    }


    public function fillInformation()
    {
        $setting = Setting::query()->first();
        return view('auth::information' , compact('setting'));
    }

    public function fillInformationStore(InformationRegisterRequest $request)
    {
        if(!\auth()->check())
        {
            return to_route('auth.register.view');
        }
        $inputs = [
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ];
        $user = \auth()->user();
        $user->update($inputs);
        alert()->success('شما با موفقیت ثبت نام شدید');
        return to_route('home.index');
    }


}
