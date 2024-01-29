<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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
                'success'=>true
            ]);
        }else{
            return response()->json([
                'success'=>false,
                'message'=>'Token invalid',
            ],401);
        }

        return response()->json([
            'success'=>false,
            'message'=>"Server error"
        ],500);

    }







    public function logout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success'=>true,
            'message'=>"Token deleted"
        ]);
    }





    public function register(Request $r){
        $validator = Validator::make($r->all(), [
            'email'=>['required', 'email'],
            'name'=>['required'],
            'password'=>['required', 'string', 'min:6', 'confirmed']
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ],423);
        }
        $email = User::where('email',$r->email)->first();

        if($email){
            return response()->json([
                'success'=>false,
                'message'=>'E-mail has be taken.'
            ],403);
        }
        $pass = Hash::make($r->password);
        User::create([
            'username'=>$r->email,
            'name'=>$r->name,
            'email'=>$r->email,
            'password'=>$pass
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'User has created.'
        ],201);


    }






    public function forgot(Request $r){

        $validator = Validator::make($r->all(), [
            'email'=>['required', 'email']
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ],423);
        }
        $email = $r->email;
        $existEmail = User::where('email',$email)->first();

        if(!$existEmail){
            return response()->json([
                'success'=>false,
                'message'=>'E-mail no exist'
            ],404);
        }

        $randomNumber = random_int(100000, 999999);
        try {
            Mail::send('email.forgot', ['code'=>$randomNumber], function ($message) use($email) {
                $message->subject('Recovery password');
                $message->to($email);
            });
            return response()->json([
                'success'=>true,
                'message'=>'E-mail enviado'
            ]);
        } catch (\Throwable $th) {
            Log::debug($th);
            return response()->json([
                'success'=>true,
                'message'=>'E-mail no enviado. Error de servidor.'
            ],500);
        }

    }

}

