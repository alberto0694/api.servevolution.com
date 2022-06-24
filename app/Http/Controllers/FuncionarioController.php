<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;
use App\Models\Pessoa;
use App\Helpers\ErrorResponse;
use App\Models\FuncionarioTipoServico;

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
            return response()->json(new ErrorResponse($th->getMessage()));
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

            $tpServicosFunc = FuncionarioTipoServico::where('funcionario_id', $funcionario->id);
            $tpServicosFunc->delete();

            foreach ($data['tipo_servicos'] as $tpServ) {
                FuncionarioTipoServico::create([
                    'funcionario_id' => $funcionario->id,
                    'tipo_servico_id' => $tpServ['id'],
                    'unidade_medida_id' => 1
                ]);
            }

            return response()->json($funcionario);

        } catch (\Throwable $th) {

            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function getFuncionario(Request $request, $id)
    {
        try {
            $funcionario = Funcionario::with(['pessoa', 'tipoServicos'])->find($id);
            return response()->json($funcionario);
        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteFuncionario($id)
    {
        try {

            $funcionario = Funcionario::find($id);
            $funcionario->update(['ativo' => false]);
            $funcionarios = Funcionario::with(['pessoa'])
                                    ->where('ativo', true)
                                    ->get();
            return response()->json($funcionarios);

        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }

}
