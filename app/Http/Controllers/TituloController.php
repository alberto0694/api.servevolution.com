<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdemServico;
use App\Models\Financeiro\Titulo;
use App\Models\OrdemServicoStatus;
use App\Helpers\ErrorResponse;

class TituloController extends Controller
{
    public function ordemServicoList(Request $request)
    {
        try{

            $ordem_servicos = OrdemServico::where('ativo', true)->get();
            $ordem_servicos = collect($ordem_servicos)
                                 ->filter(fn($o) => $o->active_status?->descricao === 'finalizado')
                                 ->values();

            return response()->json($ordem_servicos);

        }
        catch (\Throwable $th)
        {
            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function tituloList(Request $request)
    {
        try{

            $ordem_servicos = OrdemServico::where('ativo', true)->get();
            $ordem_servicos = collect($ordem_servicos)
                                 ->filter(fn($o) => $o->active_status?->descricao === 'finalizado')
                                 ->values();

            return response()->json($ordem_servicos);

        }
        catch (\Throwable $th)
        {
            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }    

    public function gerarTitulo(Request $request)
    {
        try{
            $body = $request->all();

           
            $ordem_servicos = collect($body)->map(function ($ordem) {

                Titulo::create([
                    'valor_nominal' => $ordem['valor'] ?? 0,
                    'valor_atualizado' => 0,
                    'valor_baixado' => 0,
                    'saldo' => 0
                ]);

                OrdemServicoStatus::create([
                    'ordem_servico_id' => $ordem['id'],
                    'descricao' => 'faturado'
                ]);

                return $ordem;
            });

            return response()->json($ordem_servicos);

        }
        catch (\Throwable $th)
        {
            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }


}
