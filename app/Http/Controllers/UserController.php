<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request){

        return response()->json([
            'success'=>true,
            'message'=>'Updated'
        ],201);
    }
}
