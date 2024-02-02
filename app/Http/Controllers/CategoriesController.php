<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
