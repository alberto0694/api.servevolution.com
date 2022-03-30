<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Pessoa;

class FuncionarioController extends Controller
{

    public function getFuncionarios(Request $request)
    {
        try {

            $funcionarios = Funcionario::with(['pessoa'])
                                    ->where('ativo', true)
                                    ->get();

            return response()->json($funcionarios);
        } catch (\Throwable $th) {

            return response()->json($th->getMessage());
        }
    }

    public function createOrUpdate(Request $request)
    {
        try {

            $data = $request->all();
            $funcionario = null;

            if (!isset($data['id'])) {

                $pessoa = Pessoa::create($data['pessoa']);
                $data['pessoa_id'] = $pessoa->id;
                $funcionario = Funcionario::create($data);

            } else {

                $funcionario = Funcionario::find($data['id']);
                $funcionario->update($data);

                $pessoa = Pessoa::find($data['pessoa']['id']);
                $pessoa->update($data['pessoa']);

            }

            return response()->json($funcionario);

        } catch (\Throwable $th) {

            return response()->json($th->getMessage());
        }
    }

    public function getFuncionario(Request $request, $id)
    {
        try {
            $funcionario = Funcionario::with('pessoa')->find($id);
            return response()->json($funcionario);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteFuncionario($id)
    {
        try {

            $funcionario = Funcionario::find($id);
            $funcionario->update(['ativo' => false]);

        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }
}
