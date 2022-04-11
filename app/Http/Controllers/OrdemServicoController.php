<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdemServico;

class OrdemServicoController extends Controller
{
    public function list(Request $request)
    {
        try{

            $ordem_servicos = OrdemServico::where('ativo', true)->with('funcionarios')->get();
            return response()->json($ordem_servicos);

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
            $ordem_servico = null;

            if (!isset($data['id'])) {

                $ordem_servico = OrdemServico::create($data);

            } else {

                $ordem_servico = OrdemServico::find($data['id']);
                $ordem_servico->update($data);

            }

            return response()->json($ordem_servico);

        } catch (\Throwable $th) {

            return response()->json($th->getMessage());
        }
    }

    public function getOrdemServico(Request $request, $id)
    {
        try {
            $ordem_servico = OrdemServico::with('funcionarios')->find($id);
            return response()->json($ordem_servico);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteOrdemServico($id)
    {
        try {

            $ordem_servico = OrdemServico::find($id);
            $ordem_servico->update(['ativo' => false]);

            $ordem_servicos = OrdemServico::where('ativo', true)->get();
            return response()->json($ordem_servicos);

        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }
}
