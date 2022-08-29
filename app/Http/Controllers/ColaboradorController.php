<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colaborador;
use App\Models\Pessoa;
use App\Models\Usuario;
use App\Helpers\ErrorResponse;

class ColaboradorController extends Controller
{

    public function getColaboradores(Request $request)
    {
        try {
            $colaboradores = Colaborador::with(['pessoa'])->where('ativo', true)->get();
            return response()->json($colaboradores);

        } catch (\Throwable $th) {
            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function createOrUpdate(Request $request)
    {
        try {

            $data = $request->all();
            $colaborador = null;

            if (!isset($data['id'])) {

                $pessoa = Pessoa::create($data['pessoa']);
                $data['pessoa_id'] = $pessoa->id;
                
                $pessoa = Pessoa::create($data['pessoa']);
                $data['pessoa_id'] = $pessoa->id;

                $colaborador = Colaborador::create($data);

                $usuario = Usuario::create([
                    'name' => $pessoa->razao ?? $pessoa->apelido,
                    'email' => $pessoa->email,
                    'pessoa_id' => $pessoa->id,
                    'password' => bcrypt($data['senha'] ?? '')
                ]);

            } else {

                $colaborador = Colaborador::find($data['id']);
                $colaborador->update($data);

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

            return response()->json($colaborador);

        } catch (\Throwable $th) {

            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function getColaborador(Request $request, $id)
    {
        try {
            $colaborador = Colaborador::with(['pessoa'])->find($id);
            return response()->json($colaborador);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteColaborador($id)
    {
        try {

            $colaborador = Colaborador::find($id);
            $colaborador->update(['ativo' => false]);
            $colaboradores = Colaborador::with(['pessoa'])
                                    ->where('ativo', true)
                                    ->get();
            return response()->json($colaboradores);

        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }

}
