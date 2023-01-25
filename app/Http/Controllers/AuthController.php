<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{
    public function login(Request $r){

        $r->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $email = $r->email;
        $password = $r->password;
        $remember = $r->remember ? true : false;

        $intento = filter_var($email, FILTER_VALIDATE_EMAIL) ?
        ['email' => $email, 'password' => $password, 'active' => 1] :
        ['username' => $email, 'password' => $password, 'active' => 1];

        if (Auth::attempt($intento,$remember)) {
            /* $r->session()->regenerate();
            return redirect()->route('home'); */

            $user = User::where('email',$email)->orWhere('username',$email)->firstOrFail();

            if($user){
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    'response'=>true,
                    'error'=>false,
                    'message'=>'Logget in',
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
            'response'=>false,
            'error'=>true,
            'Message'=>'Credentials are not valid'
        ]);

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

