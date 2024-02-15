<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Movement;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    
    public function movementsByCategory(Request $r) : JsonResponse {

        try {
        
        $primerDia = Carbon::now()->firstOfMonth()->format('Y-m-d');
        $hoy = Carbon::now()->format('Y-m-d');
        $desde = ($r->desde ? $r->desde : $primerDia) . ' 00:00:00';
        $hasta = ($r->hasta ? $r->hasta : $hoy) . ' 23:59:59';

        $user = $r->user();
            $results = Movement::where('category_id',$r->id)
            ->join('categories as c','category_id','=','c.id')
            ->select('movements.id','c.description as category','value','tipo','movements.description','category_id','movements.created_at')
            ->where('movements.user_id',$user->id)
            ->whereBetween('movements.created_at', [$desde, $hasta])->get();

        return response()->json(
            [
                'success'=>true,
                'results'=>$results
            ]
        );
        } catch (\Throwable $th) {
            Log::error($th);
            return response()->json(
                [
                    'success'=>false,
                    'message'=>'Servidor error'
                ],500
            );
        }
    }

    public function index() : JsonResponse
    {
        $cat = Category::all();
        return response()->json([
            'success'=>true,
            'results'=>$cat
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        $valida = Validator::make($r->all(), [
            'description'=>['required']
        ]);

        if($valida->fails()){
            return response()->json([
                'success' => false,
                'message' => $valida->errors()
            ],425);
        }
        $user = $r->user();
        $cat = Category::create([
            'icon'=>$r->icon,
            'description'=>$r->description,
            'user_id'=>$user->id
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Stored',
            'results'=>$cat
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
            'description'=>'required'
        ]);

        if($valida->fails()){
            return response()->json([
                'success' => false,
                'message' => $valida->errors()
            ],425);
        }

        $cat = Category::find($id);
        $cat->update([
            'icon'=>$r->icon,
            'description'=>$r->description
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Updated!',
            'results'=>$cat
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
        $cat = Category::find($id);
        $cat->destroy();
        return response()->json([
            'success'=>true,
            'message'=>'Deleted!',
            'results'=>$cat
        ]);
    }
}
