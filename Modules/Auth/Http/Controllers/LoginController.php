<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Service\Message\Email\EmailService;
use App\Http\Service\Message\MessageService;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Melipayamak\MelipayamakApi;
use Modules\Admin\Entities\Setting\Settinge;
use Modules\Admin\Entities\Setting\SmsSetting;
use Modules\Auth\Entities\Otp;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;

class LoginController extends Controller
{


    public function storeLogin(LoginRequest $request)
    {
        $inputs = $request->all();

        //check id is email or not
        if(filter_var($inputs['id'], FILTER_VALIDATE_EMAIL))
        {
            $type = 1; // 1 => email
            $user = User::query()->where('email', $inputs['id'])->first();
            if(empty($user)){
                return response()->json([
                    'msg' => 'ایمیل وارد شده وجود ندارد'
                ]);
            }
            if(Hash::check($request->password , $user->password ))
            {
                $accessToken = $user->createToken($user->email)->plainTextToken;
                Auth::loginUsingId($user->id);
                return  response()->json([
                    'user' => Auth::user(),
                    'accessToken' => $accessToken
                ]);
            }else{
                return response()->json([
                    'msg' => 'رمز عبور وارد شده اشتباه است !'
                ]);
            }
        }

        //check id is mobile or not
        elseif(preg_match('/^(\+98|98|0)9\d{9}$/', $inputs['id'])){
            $type = 0; // 0 => mobile;

            // all mobile numbers are in on format 9** *** ***
            $inputs['id'] = ltrim($inputs['id'], '0');
            $inputs['id'] = substr($inputs['id'], 0, 2) === '98' ? substr($inputs['id'], 2) : $inputs['id'];
            $inputs['id'] = str_replace('+98', '', $inputs['id']);
            $user = User::query()->where('mobile', $inputs['id'])->first();
            if(empty($user)){
                return response()->json([
                    'msg' => 'شماره همراه وارد شده وجود ندارد !'
                ]);
            }
            if(Hash::check($request->password , $user->password))
            {
                $accessToken = $user->createToken($user->mobile)->plainTextToken;
                Auth::loginUsingId($user->id);
                return  response()->json([
                    'user' => Auth::user(),
                    'accessToken' => $accessToken
                ]);
            }else{
                return response()->json([
                    'msg' => 'رمز عبور وارد شده اشتباه است !'
                ]);
            }
        }
        else{
            return response()->json([
                'msg' => 'شناسه ورودی نه شماره موبایل است نه ایمیل'
            ]);
        }

    }


    public function viewInfoLoginStore(RegisterRequest $request)
    {
        $inputs = $request->all();
        $setting = Settinge::query()->first();

        //check id is email or not
        if(filter_var($inputs['id'], FILTER_VALIDATE_EMAIL))
        {
            $type = 1; // 1 => email
            $user = User::query()->where('email', $inputs['id'])->first();
            if(empty($user)){
                return response()->json([
                    'msg' => 'ایمیل وارد شده وجود ندارد'
                ]);
            }
        }

        //check id is mobile or not
        elseif(preg_match('/^(\+98|98|0)9\d{9}$/', $inputs['id'])){
            $type = 0; // 0 => mobile;

            // all mobile numbers are in on format 9** *** ***
            $inputs['id'] = ltrim($inputs['id'], '0');
            $inputs['id'] = substr($inputs['id'], 0, 2) === '98' ? substr($inputs['id'], 2) : $inputs['id'];
            $inputs['id'] = str_replace('+98', '', $inputs['id']);

            $user = User::query()->where('mobile', $inputs['id'])->first();
            if(empty($user)){
                return response()->json([
                    'msg' => 'شماره همراه وارد شده وجود ندارد !'
                ]);
            }
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
            'user_id' => $user->id,
            'otp_code' => $otpCode,
            'login_id' => $inputs['id'],
            'type' => $type,
        ];

        Otp::query()->create($otpInputs);

        //send sms or email
        $title = 'کد ورود';
        $body = ' کد ورود شما به ' . $setting->title . ' :' . $otpCode ;

        if($type == 0){
            $smsSetting = SmsSetting::query()->first();
            if(!empty($smsSetting)) {
                try {
                    $username = $smsSetting->username;
                    $password = $smsSetting->password;
                    $api = new MelipayamakApi($username, $password);
                    $sms = $api->sms();
                    $to = '0' . $user->mobile;
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
            $emailService->setTo($user->email);

            $messageService = new MessageService($emailService);
            $messageService->send();
        }

        return response()->json([
            'token' => $token
        ]);

    }

    public function viewConfirmLogin($token)
    {
        $setting = Settinge::query()->first();
        $otp = Otp::query()->where('token' , $token)->first();
        if(empty($otp))
        {
            return response()->json([
                'msg' => 'آدرس وارد شده نامعتبر است'
            ] , 403);
        }
        $loginId = $otp->login_id;
        return response()->json([
            'token' => $token,
            'loginId' => $loginId,
            'setting' => $setting
        ]);
    }

    public function storeConfirmLogin(Request $request  , $token)
    {
        $request->validate([
            'otp_code' => 'required|min:6|max:6'
        ]);
        $inputs = $request->all();
        $otp = Otp::query()->where('token' , $token)->where('used' , 0)->first();
        if(empty($otp)){
            return response()->json([
                'msg' => 'آدرس وارد شده نامعتبر است'
            ] , 403);
        }

        if($otp->otp_code !== $inputs['otp_code']){
            return response()->json([
                'msg' => 'آدرس وارد شده نامعتبر است'
            ] , 403);
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
        $accessToken = $user->createToken($otp->login_id)->plainTextToken;
        Auth::loginUsingId($user->id);
        return  response()->json([
            'user' => Auth::user(),
            'accessToken' => $accessToken
        ]);
    }

    public function logout ()
    {
        $user = Auth::user();
        $user->tokens()->delete();
        return response()->json([
           'msg' => 'شما با موفقیت خارج شدید'
        ]);
    }

}
