<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Validate;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Jobs\SendEmail;
use App\Models\Level\Level;
use App\Models\Level\UserLevel;
use App\Models\User\AppUser;
use App\Models\User\User;
use App\Models\User\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'account_type' => 'required',
            'login_method' => 'required',
            'password' => 'required|confirmed|min:6',

        ];

        $validator = Validate::validateRequest($request, $rules);
        if ($validator != 'valid') return $validator;
        if ($request->login_method == 'normal') {
            $rules['mobile'] = 'required|unique:app_users,mobile';
            $validator = Validate::validateRequest($request, $rules);
            if ($validator != 'valid') return $validator;
        }
        $user = AppUser::where('email', $request->email)->first();
        if (!$user) {
            $user = new AppUser();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->account_type = $request->account_type;
            $user->password = Hash::make($request->password);
            if ($request->login_method == 'normal') {
                if ($request->has('image') and $request->image != null) {
                    $imageName = $request->image->store('public/user');
                    $user->image = $imageName;
                }
            } else if ($request->login_method == 'social') {
                if ($request->has('image') and $request->image != null) {
                    $user->image = '*facebook*' . $request->image;
                }
            }

            $user->save();
            $token = $user->createToken('Graphic Town Registration token')->plainTextToken;
            return response()->json(['user' => $user, 'token' => $token,'currentCartCount'=>0, 'status' => true], 200);

        } else {
            if (Hash::check($request->password, $user->password)) {

                $currentCart = $user->carts()->where('status', 0)->orderBy('id', 'desc')->first();
                $currentCartCount = 0;
                if ($currentCart)
                    $currentCartCount = $currentCart->items->count();

                if ($request->login_method == 'normal') {
                    if ($request->has('image') and $request->image != null) {
                        $imageName = $request->image->store('public/user');
                        $user->image = $imageName;
                        $user->save();

                    }
                } else if ($request->login_method == 'social') {
                    if ($request->has('image') and $request->image != null) {
                        $user->image = '*facebook*' . $request->image;
                        $user->save();
                    }
                }
                $token = $user->createToken('Graphic Town Registration token')->plainTextToken;
                return response()->json(['user' => $user, 'token' => $token,'currentCartCount'=>$currentCartCount, 'status' => true], 200);

            }
        }

        return response()->json(['message' => 'invalid username or password', 'status' => false], 422);

    }

}
