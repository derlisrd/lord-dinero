<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function update(Request $request){
       $validate =  Validator::make($request->all(),[
            'email'=>['email']
       ]);

        return response()->json([
            'success'=>true,
            'message'=>'Updated'
        ],201);
    }
}
