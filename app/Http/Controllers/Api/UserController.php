<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Validate;
use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Models\User\AppUser;
use App\Models\User\User;
use App\Models\User\UserAddress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function showUserProfile()
    {
        $user = auth('sanctum')->user();

        return response()->json(['data' => $user], 200);
    }

    public function updateUserProfile(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|unique:app_users,email,' . auth('sanctum')->user()->id,
            'mobile' => 'required|unique:app_users,mobile,' . auth('sanctum')->user()->id,

        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator != 'valid') return $validator;

        $user = auth('sanctum')->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->address = $request->address;
        if ($request->has('image') and $request->image != null) {
            $imageName = $request->image->store('public/user');
            $user->image = $imageName;
        }
        $user->save();
        return response()->json(['user' => $user, 'status' => true], 200);


    }

    public function updatePassword(Request $request)
    {
        $user = auth('sanctum')->user();
        $rules = [
            'password' => 'required|confirmed|min:6',
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    return $fail(__('The current password is incorrect.'));
                }
            }],
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator != 'valid') return $validator;


        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['user' => $user, 'status' => true], 200);


    }

    public function sendOtpCode(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator != 'valid') return $validator;

        $user = AppUser::where('email', $request->email)->first();
        if (!$user)
            return response()->json(['message' => 'Email not found', 'status' => 0], 200);
        $otpCode = mt_rand(10000, 99999);
        $user->otp_code = $otpCode;
        $user->save();
        $data = ['otpCode' => $otpCode];
        SendEmail::dispatch($request->email, 'email.verify_reset_password'
            , __('email.email_reset_password_verify_subject') . $data['otpCode'], $data);
        // here we sent otp code to mobile phone
        return response()->json([ 'status' => 1,'otp_code'=>$otpCode], 200);


    }

    public function verifyOtpCode(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'otp_code' => 'required',
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator != 'valid') return $validator;

        $user = AppUser::where('email', $request->email)->where('otp_code', $request->otp_code)->first();
        if (!$user)
            return response()->json(['message' => 'Verification failed', 'status' => 0], 200);

        $token = $user->createToken('Graphic Town Registration token')->plainTextToken;
        return response()->json(['user' => $user, 'token' => $token, 'status' => true], 200);


    }
    public function updateForgotPassword(Request $request)
    {
        $user = auth('sanctum')->user();
        $rules = [
            'password' => 'required|confirmed|min:6'
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator != 'valid') return $validator;


        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json(['user' => $user, 'status' => true], 200);


    }
    public function updateUserImage(Request $request)
    {
        $rules = [
            'image' => 'required'
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator !== 'valid') return $validator;

        $user = auth('sanctum')->user();

        $user->image = $request->image->store('public/users');

        $user->save();

        $hiddenValues = ['login_method', 'otp_code'];
        return response()->json(['data' => $user->makeHidden($hiddenValues)], 200);
    }

    /*
     * private functions
     * this function used to complete public functions
     *  */

}
