<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoCustoServico;

class TipoCustoServicoController extends Controller
{
    public function list(Request $request)
    {
        try{

            $tipos = TipoCustoServico::where('ativo', true)->get();
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

                $tipo = TipoCustoServico::create($data);

            } else {

                $tipo = TipoCustoServico::find($data['id']);
                $tipo->update($data);

            }

            return response()->json($tipo);

        } catch (\Throwable $th) {

            return response()->json($th->getMessage());
        }
    }

    public function getTipoCustoServico(Request $request, $id)
    {
        try {
            $tipo = TipoCustoServico::find($id);
            return response()->json($tipo);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteTipoCustoServico($id)
    {
        try {

            $tipo = TipoCustoServico::find($id);
            $tipo->update(['ativo' => false]);

            $tipos = TipoCustoServico::where('ativo', true)->get();
            return response()->json($tipos);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}
