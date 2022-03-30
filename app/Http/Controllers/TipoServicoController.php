<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoServico;

class TipoServicoController extends Controller
{
    public function list(Request $request)
    {
        try{

            $tipos = TipoServico::where('ativo', true)->get();
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

                $tipo = TipoServico::create($data);

            } else {

                $tipo = TipoServico::find($data['id']);
                $tipo->update($data);

            }

            return response()->json($tipo);

        } catch (\Throwable $th) {

            return response()->json($th->getMessage());
        }
    }

    public function getTipoServico(Request $request, $id)
    {
        try {
            $tipo = TipoServico::find($id);
            return response()->json($tipo);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteTipoServico($id)
    {
        try {

            $tipo = TipoServico::find($id);
            $tipo->update(['ativo' => false]);

            $tipos = TipoServico::where('ativo', true)->get();
            return response()->json($tipos);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

}
