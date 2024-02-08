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
use Illuminate\Support\Str;
use Carbon\Carbon;

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






    public function check(Request $r){
        $user = $r->user();
        return response()->json([
            'success'=>true,
            'results'=>[
                'username'=>$user->username,
                'email'=>$user->email,
                'token'=>$user->accessToken,
                'id'=>$user->id
            ]
        ]);
    }







    public function logout(Request $r){
        $r->user()->currentAccessToken()->delete();
        return response()->json([
            'success'=>true,
            'message'=>"Token deleted"
        ]);
    }




    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */



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


    /*
    |--------------------------------------------------------------------------
    | Reset password
    |--------------------------------------------------------------------------
    */


    public function reset(Request $r){

        $validator = Validator::make($r->all(), [
            'email'=>['required', 'email','exists:users,email'],
            'token'=>['required'],
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ],423);
        }

        $registro = PasswordReset::where('email',$r->email)->where('token',$r->token)->first();
        if(!$registro){
            return response()->json([
                'success'=>false,
                'message'=>'Invalid code',
                'registro'=>$registro
            ],404);
        }
        $ahora = Carbon::now();
        $fechaValidacion = Carbon::parse($registro->created_at);
        $minutosTranscurridos = $fechaValidacion->diffInMinutes($ahora);
        if ($minutosTranscurridos >= 15) {
            return response()->json([
                'success'=>false,
                'message'=>'Expired code'
            ],401);
        }
        $user = User::where('email',$registro->email)->first();
        $user->password = Hash::make($r->password);
        $user->save();
        $email = $r->email;
        Mail::send('email.password_reset', ['code'=>''], function ($message) use($email) {
            $message->subject('Password changed');
            $message->to($email);
        });

        return response()->json([
            'success'=>true,
            'message'=>'The password has been changed successfully'
        ]);
    }






    /*
    |--------------------------------------------------------------------------
    | Validate code to refresh password
    |--------------------------------------------------------------------------
    */

    public function code(Request $r){
        $validator = Validator::make($r->all(), [
            'email'=>['required', 'email','exists:users,email'],
            'code'=>['required']
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ],423);
        }


        $registro = PasswordReset::where('email',$r->email)->where('code',$r->code)->first();
        if(!$registro){
            return response()->json([
                'success'=>false,
                'message'=>'Invalid code'
            ],404);
        }
        $ahora = Carbon::now();
        $fechaValidacion = Carbon::parse($registro->created_at);
        $minutosTranscurridos = $fechaValidacion->diffInMinutes($ahora);
        if ($minutosTranscurridos >= 15) {
            return response()->json([
                'success'=>false,
                'message'=>'Expired code'
            ],401);
        }
        return response()->json([
            'success'=>true,
            'results'=>['token'=>$registro->token]
        ]);

    }


    /*
    |--------------------------------------------------------------------------
    | Forgot password
    |--------------------------------------------------------------------------
    */

    public function forgot(Request $r){

        $validator = Validator::make($r->all(), [
            'email'=>['required', 'email','exists:users,email'],
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ],423);
        }
        $email = $r->email;

        $randomNumber = random_int(100000, 999999);
        try {
            Mail::send('email.forgot', ['code'=>$randomNumber], function ($message) use($email) {
                $message->subject('Recovery password');
                $message->to($email);
            });
            $token = Str::random(64);
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            PasswordReset::updateOrCreate(
                ['email'=>$email],
                [
                    'email'=>$email,
                    'token'=>$token,
                    'code'=>$randomNumber,
                    'created_at'=>$datetime
                ]
            );
            return response()->json([
                'success'=>true,
                'message'=>'Please check your mail.'
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

