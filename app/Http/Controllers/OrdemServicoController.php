<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdemServico;
use App\Models\OrdemServicoCusto;
use App\Models\OrdemServicoFuncionario;

class OrdemServicoController extends Controller
{
    public function list(Request $request)
    {
        try{

            $ordem_servicos = OrdemServico::where('ativo', true)
                                    ->with('funcionarios')
                                    ->with('custos')
                                    ->get();

            return response()->json($ordem_servicos);

        }
        catch (\Throwable $th)
        {
            return response()->json($th->getMessage());
        }
    }

    private function saveOrdemServico($data)
    {
        try {

            $ordem_servico = null;

            if (!isset($data['id'])) {

                $ordem_servico = OrdemServico::create($data);

                foreach ($data['funcionarios'] as $funcionario) {

                    $os_func = OrdemServicoFuncionario::create([
                        'funcionario_id' => $funcionario['id'],
                        'ordem_servico_id' => $ordem_servico->id
                    ]);

                    $os_custos = collect($data['custos'])->filter(function($c) use($os_func) {
                            return $c['ordem_servico_funcionario']['funcionario_id'] == $os_func->funcionario_id;
                    });

                    foreach ($os_custos as $custo) {
                        OrdemServicoCusto::create([
                            'ordem_servico_funcionario_id' => $os_func->id,
                            'valor' => $custo['valor'],
                            'tipo_custo_id' => $custo['tipo_custo_id']
                        ]);
                    }
                }

            } else {

                $ordem_servico = OrdemServico::find($data['id']);
                $ordem_servico->update($data);

                foreach ($data['funcionarios'] as $funcionario) {

                    $os_func = OrdemServicoFuncionario::where('funcionario_id', $funcionario['id'])
                                    ->where('ordem_servico_id', $ordem_servico->id)
                                    ->first();

                    if(empty($os_func)){
                        $os_func = OrdemServicoFuncionario::create([
                            'funcionario_id' => $funcionario['id'],
                            'ordem_servico_id' => $ordem_servico->id
                        ]);
                    }

                    $os_custos = collect($data['custos'])->filter(function($c) use($os_func) {
                        return $c['ordem_servico_funcionario']['funcionario_id'] == $os_func->funcionario_id;
                    });

                    foreach ($os_custos as $custo) {

                        if(empty($custo['id'])){
                            OrdemServicoCusto::create([
                                'ordem_servico_funcionario_id' => $os_func->id,
                                'valor' => $custo['valor'],
                                'tipo_custo_id' => $custo['tipo_custo_id']
                            ]);
                        } else {
                            OrdemServicoCusto::find($custo['id'])
                                ->update([
                                    'ordem_servico_funcionario_id' => $os_func->id,
                                    'valor' => $custo['valor'],
                                    'tipo_custo_id' => $custo['tipo_custo_id']
                                ]);
                        }
                    }
                }
            }

            return OrdemServico::with('funcionarios.pessoa')
                                    ->with('custos.ordemServicoFuncionario')
                                    ->find($ordem_servico->id);
        }
        catch (\Throwable $th)
        {
            return response()->json($th->getMessage());
        }
    }

    public function createOrUpdate(Request $request)
    {
        try {

            $ordem_servico = $this->saveOrdemServico($request->all());
            return response()->json($ordem_servico);

        } catch (\Throwable $th) {

            return response()->json($th->getMessage());
        }
    }

    public function getOrdemServico(Request $request, $id)
    {
        try {
            $ordem_servico = OrdemServico::with('funcionarios.pessoa')
                                    ->with('custos.ordemServicoFuncionario')
                                    ->find($id);

            return response()->json($ordem_servico);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteOrdemServico($id)
    {
        try {

            $ordem_servico = OrdemServico::find($id);
            $ordem_servico->update(['ativo' => false]);

            $ordem_servicos = OrdemServico::where('ativo', true)->get();
            return response()->json($ordem_servicos);

        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }

    public function deleteFuncionarioOrdemServico(Request $request, $ordem_servico_id, $funcionario_id)
    {
        try {

            $ordem_servico = $this->saveOrdemServico($request->all());

            $ordem_servico_func = OrdemServicoFuncionario::where('ordem_servico_id', $ordem_servico_id)
                                    ->where('funcionario_id', $funcionario_id)
                                    ->first();



            if(!empty($ordem_servico_func))
            {
                $ordem_servico_func->delete();
            }

            $ordem_servico =  OrdemServico::with('funcionarios.pessoa')
                                    ->with('custos.ordemServicoFuncionario')
                                    ->find($ordem_servico->id);

            return response()->json($ordem_servico);

        } catch (\Throwable $e) {
            return response()->json($e->getMessage());
        }
    }
}
