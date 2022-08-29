<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Financeiro\Parcela;
use App\Models\Financeiro\Transacao;
use App\Models\Financeiro\Titulo;
use App\Helpers\ErrorResponse;

class FinanceiroController extends Controller
{

    public function transacoesList(Request $request)
    {
        try {

            $parcela_id = $request->query('parcela_id');
            $transacoes = Transacao::where('parcela_id', $parcela_id)->get();

            return response()->json($transacoes);
        } catch (\Throwable $th) {

            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function baixarParcela(Request $request)
    {
        try {

            $body = $request->all();
            $parcela = Parcela::find($body['parcela_id']);
            $parcela->valor_baixado += $body['valor_baixado'];

            if ($parcela->valor_baixado > $parcela->valor_nominal) {
                return response()->json(new ErrorResponse("O valor total baixado é maior que o valor nominal da parcela, verifique!"));
            }

            $parcela->saldo = $parcela->valor_nominal - $parcela->valor_baixado;
            $parcela->save();

            $valorBaixadoTitulo = DB::table('parcela')
                ->where('titulo_id', $parcela->titulo_id)
                ->sum('valor_baixado');

            $titulo = Titulo::find($parcela->titulo_id);
            $titulo->valor_baixado = $valorBaixadoTitulo;
            $titulo->saldo = $titulo->valor_nominal - $titulo->valor_baixado;
            $titulo->save();

            Transacao::create($body);
            $transacoes = Transacao::where('parcela_id', $body['parcela_id'])->get();
            return response()->json($transacoes);

        } catch (\Throwable $th) {

            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }
}
