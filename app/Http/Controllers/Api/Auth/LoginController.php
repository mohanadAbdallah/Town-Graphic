<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Validate;
use App\Http\Controllers\Controller;
use App\Models\User\AppUser;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];
        $validator = Validate::validateRequest($request, $rules);
        if ($validator != 'valid') return $validator;

        $user = AppUser::where('email', $request->email)->first();
        if (Hash::check($request->password, $user->password)) {
            $token = $user->createToken('Graphic Town Registration token')->plainTextToken;
            $currentCart = $user->carts()->where('status', 0)->orderBy('id', 'desc')->first();
            $currentCartCount = 0;
            if ($currentCart)
                $currentCartCount = $currentCart->items->count();
            return response()->json(['user' => $user, 'token'=> $token,'currentCartCount'=>$currentCartCount, 'status' => true], 200);

        }

        return response()->json(['message' => 'invalid username or password', 'status' => false], 422);

    }
}
