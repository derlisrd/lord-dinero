<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{



    public function login(Request $r){

        $validator = Validator::make($r->all(), [
            'email'=>'required',
            'password'=>'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ],425);
        }

        $email = $r->email;
        $password = $r->password;
        $remember = $r->remember ? true : false;

        $intento = filter_var($email, FILTER_VALIDATE_EMAIL) ?
        ['email' => $email, 'password' => $password, 'active' => 1] :
        ['username' => $email, 'password' => $password, 'active' => 1];

        if (Auth::attempt($intento,$remember)) {
            $user = User::where('email',$email)->orWhere('username',$email)->firstOrFail();

            if($user){
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'success'=>true,
                    'results'=>[
                        'username'=>$user->username,
                        'email'=>$user->email,
                        'token'=>$token,
                        'id'=>$user->id
                    ]
                ]);

            }
        }


        return response()->json([
            'success'=>false,
            'message'=>'Credentials are not valid'
        ],401);

    }






    public function check(){
        if(auth('sanctum')->check()){
            return response()->json([
                'results'=>[
                    'username'=>auth('sanctum')->user()->username,
                    'email'=>auth('sanctum')->user()->email,
                    'id'=>auth('sanctum')->user()->id
                ],
                'response'=>true,
                'error'=>false,
                'message'=>'Valid',
            ]);
        }else{
            return response()->json([
                'results'=>null,
                'response'=>false,
                'error'=>false,
                'message'=>'Token invalid',
            ]);
        }

    }





    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
    }

}

