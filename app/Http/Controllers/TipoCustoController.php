<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoCusto;

class TipoCustoController extends Controller
{
    public function list(Request $request)
    {
        try{

            $tipos = TipoCusto::where('ativo', true)->get();
            return response()->json($tipos);

    }
        catch (\Throwable $th)
        {
            return response()->json($th->getMessage());
        }
    }

    public function createOrUpdate(Request $request)
    {
        try {

            $data = $request->all();
            $tipo = null;

            if (!isset($data['id'])) {

                $tipo = TipoCusto::create($data);

            } else {

                $tipo = TipoCusto::find($data['id']);
                $tipo->update($data);

            }

            return response()->json($tipo);

        } catch (\Throwable $th) {

            return response()->json($th->getMessage());
        }
    }

    public function getTipoCusto(Request $request, $id)
    {
        try {
            $tipo = TipoCusto::find($id);
            return response()->json($tipo);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteTipoCusto($id)
    {
        try {

            $tipo = TipoCusto::find($id);
            $tipo->update(['ativo' => false]);

            $tipos = TipoCusto::where('ativo', true)->get();
            return response()->json($tipos);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}
