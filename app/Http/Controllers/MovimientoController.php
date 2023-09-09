<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovimientoController extends Controller
{
    /**
     * Lista todos los movimientos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Movimiento::all();
    }

    /**
     * Devuelve un movimiento específico.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Movimiento::find($id);
    }

    /**
     * Crea un nuevo movimiento.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = $request->user;
        $validator = Validator::make($request->all(), [
            'detalles' => 'required|string|min:5',
            'tipo' => 'required|string|max:2',
            'valor' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $movimiento = new Movimiento();
        $movimiento->user_id = $user->id;
        $movimiento->detalles = $request->input('detalles');
        $movimiento->tipo = $request->input('tipo');
        $movimiento->valor = $request->input('valor');
        $movimiento->save();

        return $movimiento;
    }

    /**
     * Actualiza un movimiento existente.
     *
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $movimiento = Movimiento::find($id);
        $movimiento->user_id = $request->input('user_id');
        $movimiento->detalles = $request->input('detalles');
        $movimiento->tipo = $request->input('tipo');
        $movimiento->valor = $request->input('valor');
        $movimiento->save();

        return $movimiento;
    }

    /**
     * Elimina un movimiento existente.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $movimiento = Movimiento::find($id);
        $movimiento->delete();

        return response()->json(['success' => true]);
    }
}
