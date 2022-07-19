<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Pessoa;
use App\Models\Usuario;
use App\Helpers\ErrorResponse;

class FinanceiroController extends Controller
{
    
    public function contasReceberList()
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

}
