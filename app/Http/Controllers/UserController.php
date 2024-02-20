<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function me(Request $request){
        $user = $request->user();

        return response()->json([
            'success'=>true,
            'message'=>$user
        ],201);

    }
    public function update(Request $request){
       $validate =  Validator::make($request->all(),[
            'email'=>['email']
       ]);

        return response()->json([
            'success'=>true,
            'message'=>'Updated'
        ],201);
    }



    public function destroy(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'password'=>['required']
        ]);

        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ],423);
        }
        $credentials = $request->password;

        if (!Hash::check($credentials, $user->password)) {
            return response()->json(['success' =>false,'message'=>'Contraseña incorrecta'], 401);
        }
        $u = User::find($user->id);
        $u->tokens()->delete();
        $u->delete();

        return response()->json([
            'success'=>true,
            'message'=>'deleted'
        ],201);
    }


}
