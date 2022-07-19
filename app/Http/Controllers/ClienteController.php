<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use App\Models\Pessoa;
use App\Models\Usuario;
use App\Helpers\ErrorResponse;
use App\Models\ValoresServicos;

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

            return response()->json($valoresServicos);

        } catch (\Throwable $th) {

            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function getCliente(Request $request, $id)
    {
        try {
            $cliente = Cliente::with(['pessoa', 'valoresServicos.unidadeMedida', 'valoresServicos.tipoServico'])->find($id);
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

}
