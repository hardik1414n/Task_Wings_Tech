<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    public function loginCheck(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'email'=>['required','email'],
            'password'=>['required']
        ]);

        if($validate->fails()){
            return response()->json([
                'errors'=>$validate->errors()
            ]);
        }

        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password]))
        {
            $user = Auth::user();
            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'token'=>$token,
            ]);
        }else{
            return response()->json([
                'errors'=>['invalid'=>'Please Enter Valid Credentials']
            ]);
        }

    }

    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json(true);
    }


}