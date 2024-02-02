<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovementsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $r) : JsonResponse
    {

       $primerDia = Carbon::now()->firstOfMonth()->format('Y-m-d');
       $hoy = Carbon::now()->format('Y-m-d');
       $desde = ($r->desde ? $r->desde : $primerDia) . ' 00:00:00';
       $hasta = ($r->hasta ? $r->hasta : $hoy) . ' 23:59:59';

       $user = $r->user();
       $result = Movement::where('user_id',$user->id)->whereBetween('created_at', [$desde, $hasta])->get();
       return response()->json([
        'success'=>true,
        'results'=>$result
       ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r) : JsonResponse
    {
        $valida = Validator::make($r->all(), [
            'value'=>['required','numeric'],
            'category_id'=>'required',
            'description'=>'required'
        ]);

        if($valida->fails()){
            return response()->json([
                'success' => false,
                'message' => $valida->errors()
            ],425);
        }
        $user = $r->user();
        $mov = Movement::create([
            'value'=>$r->value,
            'category_id'=>$r->category_id,
            'description'=>$r->description,
            'user_id'=>$user->id
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Stored',
            'results'=>$mov
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $r, $id)
    {
        $valida = Validator::make($r->all(), [
            'value'=>['required','numeric'],
            'category_id'=>'required',
            'description'=>'required'
        ]);

        if($valida->fails()){
            return response()->json([
                'success' => false,
                'message' => $valida->errors()
            ],425);
        }

        $mov = Movement::find($id);
        $mov->update([
            'value'=>$r->value,
            'category_id'=>$r->category_id,
            'description'=>$r->description
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Updated!',
            'results'=>$mov
        ]);


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $mov = Movement::find($id);
        $mov->destroy();
        return response()->json([
            'success'=>true,
            'message'=>'Deleted!',
            'results'=>$mov
        ]);
    }
}
