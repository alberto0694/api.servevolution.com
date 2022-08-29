<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Pessoa;
use App\Models\Usuario;
use App\Helpers\ErrorResponse;
use App\Models\ValoresServicos;
use App\Models\ValoresFuncionarios;

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
            return response()->json(new ErrorResponse($th->getMessage()));
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
                    'password' => bcrypt($data['senha'] ?? '')
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
                    'pessoa_id' => $pessoa->id
                ]);

                if(isset($data['senha'])){
                    $usuario ->update([
                        'password' => bcrypt($data['senha'])
                    ]);
                }
            }

            return response()->json($cliente);

        } catch (\Throwable $th) {

            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function createOrUpdateValorServico(Request $request)
    {
        try {

            $data = $request->all();
            
            if (!isset($data['id'])) {

                $valorServico = ValoresServicos::create($data);
                
            } else {

                $valorServico = ValoresServicos::find($data['id']);
                $valorServico->update($data);
            }

            $valoresServicos = ValoresServicos::with(['unidadeMedida', 'tipoServico'])
                                                ->where('ativo', true)
                                                ->where('cliente_id', $data['cliente_id'])
                                                ->get();

            //print_r(json_encode($valoresServicos, JSON_NUMERIC_CHECK));
            return response()->json($valoresServicos, 200);

        } catch (\Throwable $th) {

            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function createOrUpdateValorFuncionario(Request $request)
    {
        try {

            $data = $request->except('cliente_id');
            $cliente_id = $request->query('cliente_id');
        
            collect($data)->each(function($valorFuncionarioItem) {
                if (!isset($valorFuncionarioItem['id'])) {

                    ValoresFuncionarios::create($valorFuncionarioItem);
                    
                } else {
    
                    $valorFuncionario = ValoresFuncionarios::find($valorFuncionarioItem['id']);
                    $valorFuncionario->update($valorFuncionarioItem);
                }

            });

            $valores = ValoresFuncionarios::with(['unidadeMedida', 'tipoServico', 'funcionario.pessoa'])
                    ->where('ativo', true)
                    ->where('cliente_id', $cliente_id)
                    ->get();

            return response()->json($valores, 200);

        } catch (\Throwable $th) {

            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function getCliente(Request $request, $id)
    {
        try {
            $cliente = Cliente::with([
                                    'pessoa', 
                                    'valoresServicos.unidadeMedida', 
                                    'valoresServicos.tipoServico',
                                    'valoresFuncionarios.tipoServico',
                                    'valoresFuncionarios.unidadeMedida',
                                    'valoresFuncionarios.funcionario.pessoa'
                                ])->find($id);

            return response()->json($cliente);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteCliente($id)
    {
        try {

            $cliente = Cliente::find($id);
            $cliente->update(['ativo' => false]);

            $clientes = Cliente::where('ativo', true)->with('pessoa')->get();
            return response()->json($clientes);

        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteValorServico($id)
    {
        try {

            $valor_servico = ValoresServicos::find($id);
            $valor_servico->update(['ativo' => false]);

            $valores_servico = ValoresServicos::with(['unidadeMedida', 'tipoServico'])
                                                ->where('ativo', true)
                                                ->where('cliente_id', $valor_servico->cliente_id)
                                                ->get();

            return response()->json($valores_servico);

        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteValorFuncionario(Request $request)
    {
        try {

            $ids = $request->except('cliente_id');
            $cliente_id = $request->query('cliente_id');

            collect($ids)->each(function($id){
                $valor_func = ValoresFuncionarios::find($id);
                $valor_func->update(['ativo' => false]);
            });

            $valores = ValoresFuncionarios::with(['unidadeMedida', 'tipoServico', 'funcionario.pessoa'])
                    ->where('ativo', true)
                    ->where('cliente_id', $cliente_id)
                    ->get();

            return response()->json($valores, 200);

        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }
}
