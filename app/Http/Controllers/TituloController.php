<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdemServico;
use App\Models\Financeiro\Titulo;
use App\Models\OrdemServicoStatus;
use App\Helpers\ErrorResponse;
use App\Models\Financeiro\Parcela;
use Carbon\Carbon;

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

            $titulos = Titulo::with(['ordemServicos', 'parcelas'])->where('ativo', true)->get();
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

            $body = collect($request->data);
            $quantidade_parcelas = (int)$request->quantidade_parcelas;

            if($body->count() > 0){
                $valor_nominal = 0;
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
    
                    $valor_nominal += ((float)$ordem_servico->valor ?? 0);
                    return $ordem;
                });

                $titulo->update([
                    'valor_nominal' => $valor_nominal,
                    'saldo' => $valor_nominal,
                    'valor_atualizado' => $valor_nominal
                ]);

                $titulo->save();

                $valor_parcela = $valor_nominal / $quantidade_parcelas;
                $data_atual = new Carbon();

                for ($i = 0; $i < $quantidade_parcelas; $i++) { 
                    $data_atual = $data_atual->addMonth();
                    Parcela::create([
                        'valor_nominal'=> $valor_parcela,
                        'valor_atualizado'=> $valor_parcela,
                        'valor_baixado'=> 0,
                        'saldo'=> $valor_parcela,
                        'titulo_id'=> $titulo->id,        
                        'vencimento'=> $data_atual
                    ]);
                }
    
                return response()->json($ordem_servicos);
            }

            return response()->json([]);
            
        }
        catch (\Throwable $th)
        {
            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function deleteTitulo($id)
    {
        try{
           
            $titulo = Titulo::find($id);
            $titulo->update(['ativo' => false]);     

            $osWhere = OrdemServico::where('titulo_id', $titulo->id);        
            collect($osWhere->get())
                ->map(function($ordem_servico){
                    
                    OrdemServicoStatus::create([
                        'ordem_servico_id' => $ordem_servico->id,
                        'descricao' => 'finalizado'
                    ]);
                });

            $osWhere->update(['titulo_id' => null]);
            $titulos = Titulo::with(['ordemServicos', 'parcelas'])->where('ativo', true)->get();
            return response()->json($titulos);            
        }
        catch (\Throwable $th)
        {
            return response()->json(new ErrorResponse($th->getMessage()));
        }        
    }


}
