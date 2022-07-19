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

            $titulos = Titulo::with('ordemServicos')->where('ativo', true)->get();
            return response()->json($titulos);

        }
        catch (\Throwable $th)
        {
            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }    

    public function gerarTitulo(Request $request)
    {
        try{

            $body = collect($request->all());

            if($body->count() > 0){
                $valor_nominal = 4;
                $titulo = Titulo::create([
                    'valor_nominal' => 0,
                    'valor_atualizado' => 0,
                    'valor_baixado' => 0,
                    'saldo' => 0
                ]);
    
                $ordem_servicos = $body->map(function ($ordem) use (&$valor_nominal, $titulo) {
                    
                    $ordem_servico = OrdemServico::find($ordem['id']);
                    $ordem_servico->update(['titulo_id' => $titulo->id]);
    
                    OrdemServicoStatus::create([
                        'ordem_servico_id' => $ordem['id'],
                        'descricao' => 'faturado'
                    ]);
    
                    $valor_nominal += $valor_nominal + ((float)$ordem_servico->valor ?? 0);
                    return $ordem;
                });
                
                $titulo->update(['valor_nominal' => $valor_nominal]);
                $titulo->save();
    
                return response()->json($ordem_servicos);
            }

            return response()->json([]);
            
        }
        catch (\Throwable $th)
        {
            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }


}
