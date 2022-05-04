<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Pessoa;
use App\Models\Usuario;
use App\Models\OrdemServico;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class ClienteController extends Controller
{
    public function list(Request $request)
    {
        try{

            $clientes = Cliente::where('ativo', true)->with('pessoa')->get();
            return response()->json($clientes);

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
            $cliente = null;

            if (!isset($data['id'])) {

                $pessoa = Pessoa::create($data['pessoa']);
                $data['pessoa_id'] = $pessoa->id;

                $cliente = Cliente::create($data);

                $usuario = Usuario::create([
                    'name' => $pessoa->razao ?? $pessoa->apelido,
                    'email' => $pessoa->email,
                    'pessoa_id' => $pessoa->id,
                    'password' => bcrypt($data['senha'])
                ]);

            } else {

                $cliente = Cliente::find($data['id']);
                $cliente->update($data);

                $pessoa = Pessoa::find($data['pessoa']['id']);
                $pessoa->update($data['pessoa']);

                $usuario = Usuario::where('pessoa_id', $pessoa->id)->first();

                $usuario ->update([
                    'name' => $pessoa->razao ?? $pessoa->apelido,
                    'email' => $pessoa->email,
                    'pessoa_id' => $pessoa->id,
                    'password' => bcrypt($data['senha'])
                ]);

            }

            return response()->json($cliente);

        } catch (\Throwable $th) {

            return response()->json($th->getMessage());
        }
    }

    public function getCliente(Request $request, $id)
    {
        try {
            $cliente = Cliente::with('pessoa')->find($id);
            return response()->json($cliente);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteCliente($id)
    {
        try {

            $cliente = Cliente::find($id);
            $cliente->update(['ativo' => false]);

            $clientes = Cliente::where('ativo', true)->get();
            return response()->json($clientes);

        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }

    public function listOrdemServico(Request $request, $cliente_id)
    {
        try{

            $ordem_servicos = OrdemServico::where('ativo', true)
                                    ->with('funcionarios')
                                    ->with('custos')
                                    ->where('cliente_id', $cliente_id)
                                    ->get();

            return response()->json($ordem_servicos);

        }
        catch (\Throwable $th)
        {
            return response()->json($th->getMessage());
        }
    }

    public function listOrdemServicoKanban(Request $request, $cliente_id)
    {
        try{

            $from = Carbon::now();
            $to = Carbon::now()->addDays(10);
            $period = CarbonPeriod::between($from, $to);

            $dates = [];
            foreach ($period as $key => $date) {
                $result = new \stdClass();
                $result->id = $key;
                $result->data = $date->format("d/m/Y");
                $result->cards = OrdemServico::whereDate('data', $date->format("Y-m-d"))->get();
                array_push($dates, $result);
            }

            return response()->json($dates);
        }
        catch (\Throwable $th)
        {
            return response()->json($th->getMessage());
        }
    }

}
