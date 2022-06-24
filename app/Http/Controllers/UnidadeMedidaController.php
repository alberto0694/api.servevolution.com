<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnidadeMedida;
use App\Helpers\ErrorResponse;

class UnidadeMedidaController extends Controller
{
    public function list(Request $request)
    {
        try{

            $unidades = UnidadeMedida::where('ativo', true)->get();
            return response()->json($unidades);

    }
        catch (\Throwable $th)
        {
            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function createOrUpdate(Request $request)
    {
        try {

            $data = $request->all();
            $unidade = null;

            if (!isset($data['id'])) {

                $unidade = UnidadeMedida::create($data);

            } else {

                $unidade = UnidadeMedida::find($data['id']);
                $unidade->update($data);

            }

            return response()->json($unidade);

        } catch (\Throwable $th) {

            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function getUnidadeMedida(Request $request, $id)
    {
        try {
            $unidade = UnidadeMedida::find($id);
            return response()->json($unidade);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteUnidadeMedida($id)
    {
        try {

            $unidade = UnidadeMedida::find($id);
            $unidade->update(['ativo' => false]);

            $unidades = UnidadeMedida::where('ativo', true)->get();
            return response()->json($unidades);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}
