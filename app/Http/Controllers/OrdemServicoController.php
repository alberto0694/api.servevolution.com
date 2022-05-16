<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdemServico;
use App\Models\OrdemServicoCusto;
use App\Models\Cliente;
use App\Models\OrdemServicoFuncionario;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Helpers\ErrorResponse;

class OrdemServicoController extends Controller
{
    public function list(Request $request)
    {
        try {

            $ordem_servicos = OrdemServico::where('ativo', true)
                ->with('funcionarios.pessoa')
                ->with('custos');

            $cliente_id = $request->query('cliente_id');
            $body = $request->all();

            if (!empty($cliente_id)) {
                $ordem_servicos = $ordem_servicos->where('cliente_id', $cliente_id);
            }

            if (!empty($body['dataInicial'])) {
                $date = Carbon::parse($body['dataInicial']);
                $ordem_servicos->whereDate('data', '>=', $date->format('Y-m-d'));
            }

            if (!empty($body['dataFinal'])) {
                $date = Carbon::parse($body['dataFinal']);
                $ordem_servicos->whereDate('data', '<=', $date->format('Y-m-d'));
            }

            if (!empty($body['servicos']) && count($body['servicos']) > 0) {
                $ordem_servicos->whereIn('tipo_servico_id', $body['servicos']);
            }

            return response()->json($ordem_servicos->get());
        } catch (\Throwable $th) {
            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function listKanban(Request $request)
    {
        try {

            $cliente_id = $request->query('cliente_id');

            if (empty($cliente_id)) {

                $kanban = $this->getListKanbanByAll($request->all());
                return response()->json($kanban);
            } else {

                $kanban = $this->getListKanbanByCliente($cliente_id, $request->all());
                return response()->json($kanban);
            }
        } catch (\Throwable $th) {
            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function createOrUpdate(Request $request)
    {
        try {

            $ordem_servico = $this->saveOrdemServico($request->all());
            return response()->json($ordem_servico);
        } catch (\Throwable $th) {

            return response()->json(new ErrorResponse($th->getMessage()));
        }
    }

    public function getOrdemServico(Request $request, $id)
    {
        try {
            $ordem_servico = OrdemServico::with('funcionarios.pessoa')
                ->with('custos.ordemServicoFuncionario')
                ->find($id);

            return response()->json($ordem_servico);
        } catch (\Throwable $e) {
            return response()->json(new ErrorResponse($e->getMessage()));
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
            return response()->json(new ErrorResponse($e->getMessage()));
        }
    }

    public function deleteFuncionarioOrdemServico(Request $request, $ordem_servico_id, $funcionario_id)
    {
        try {

            $ordem_servico = $this->saveOrdemServico($request->all());

            $ordem_servico_func = OrdemServicoFuncionario::where('ordem_servico_id', $ordem_servico_id)
                ->where('funcionario_id', $funcionario_id)
                ->first();



            if (!empty($ordem_servico_func)) {
                $ordem_servico_func->delete();
            }

            $ordem_servico =  OrdemServico::with('funcionarios.pessoa')
                ->with('custos.ordemServicoFuncionario')
                ->find($ordem_servico->id);

            return response()->json($ordem_servico);
        } catch (\Throwable $e) {
            return response()->json(new ErrorResponse($e->getMessage()));
        }
    }

    private function getListKanbanByCliente($cliente_id)
    {
        $from = Carbon::now();
        $to = Carbon::now()->addDays(10);
        $period = CarbonPeriod::between($from, $to);

        return collect($period)->map(function ($date, $index) {
            $result = new \stdClass();
            $result->id = $index;
            $result->titulo = $date->format("d/m/Y");
            $result->cards = OrdemServico::with(['funcionarios.pessoa', 'custos'])->whereDate('data', $date->format("Y-m-d"))->get();
            return $result;
        });
    }

    private function getListKanbanByAll($body)
    {
        $hasFilterServico = !empty($body['servicos']) && count($body['servicos']) > 0;
        $hasFilterFuncionario = !empty($body['funcionarios']) && count($body['funcionarios']) > 0;

        $clientes = Cliente::with(['pessoa'])
            ->with('ordemServicos', function ($query) use ($body, $hasFilterServico) {
                if (!empty($body['dataInicial'])) {
                    $date = Carbon::parse($body['dataInicial']);
                    $query->whereDate('data', '>=', $date->format('Y-m-d'));
                }

                if (!empty($body['dataFinal'])) {
                    $date = Carbon::parse($body['dataFinal']);
                    $query->whereDate('data', '<=', $date->format('Y-m-d'));
                }

                if ($hasFilterServico) {
                    $query->whereIn('tipo_servico_id', $body['servicos']);
                }

                $query->with(['custos', 'funcionarios.pessoa']);
            })
            ->where('ativo', true);

        return collect($clientes->get())
            ->map(function ($cliente, $index) {
                $result = new \stdClass();
                $result->id = $index;
                $result->titulo = $cliente->pessoa->razao ?? $cliente->pessoa->apelido;
                $result->cards = $cliente->ordemServicos;
                return $result;
            })
            ->filter(function ($cliente) use ($body, $hasFilterFuncionario) {

                if ($hasFilterFuncionario) {

                    $cards = collect($cliente->cards)->filter(function ($card)  use ($body) {
                        return count(collect($card['funcionarios'])->filter(function ($funcionario) use ($body) {
                            return in_array($funcionario->id, $body['funcionarios']);
                        })) > 0;
                    });

                    $cliente->cards = $cards;
                }

                return count($cliente->cards) > 0;
            })->values();
    }

    private function saveOrdemServico($data)
    {
        $ordem_servico = null;
        if (!isset($data['id'])) {

            $ordem_servico = OrdemServico::create($data);

            foreach ($data['funcionarios'] as $funcionario) {

                $os_func = OrdemServicoFuncionario::create([
                    'funcionario_id' => $funcionario['id'],
                    'ordem_servico_id' => $ordem_servico->id
                ]);

                $os_custos = collect($data['custos'])->filter(function ($c) use ($os_func) {
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

                if (empty($os_func)) {
                    $os_func = OrdemServicoFuncionario::create([
                        'funcionario_id' => $funcionario['id'],
                        'ordem_servico_id' => $ordem_servico->id
                    ]);
                }

                $os_custos = collect($data['custos'])->filter(function ($c) use ($os_func) {
                    return $c['ordem_servico_funcionario']['funcionario_id'] == $os_func->funcionario_id;
                });

                foreach ($os_custos as $custo) {

                    if (empty($custo['id'])) {
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
}
