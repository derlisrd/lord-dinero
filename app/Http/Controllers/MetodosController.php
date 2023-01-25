<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MetodosController extends Controller
{

    public function not_found(){
        return response()->json([
            'response'=>false,
            'error'=>true,
            'message'=>'The GET method is not supported for this route'
        ]);
    }

}
