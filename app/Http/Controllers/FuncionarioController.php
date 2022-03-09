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

            $funcionarios = Funcionario::with('pessoa')
                ->get();

            return response()->json($funcionarios);
        } catch (\Throwable $th) {

            return response()->json($th->getMessage());
        }
    }


    public function createSimples(Request $request)
    {
        try {
            Funcionario::create($request->all());
        } catch (\Exception $err) {
        }
    }

    public function createOrUpdate(Request $request)
    {
        // try {

            $data = $request->all();
            $funcionario = null;

            if (!isset($data['id'])) {

                $pessoa = Pessoa::create($data['pessoa']);
                $data['pessoa_id'] = $pessoa->id;
                $funcionario = Funcionario::create($data);

            } else {

                $funcionario = Funcionario::find($$data['id']);
                $funcionario->update($data);

            }

            return response()->json($funcionario);

        // } catch (\Throwable $th) {

        //     return response()->json($th->getMessage());
        // }
    }

    public function getFuncionario(Request $request, $id)
    {
        try {
            $funcionario = Funcionario::find($id);
            return response()->json($funcionario);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }
}
