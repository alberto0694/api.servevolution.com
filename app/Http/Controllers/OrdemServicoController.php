<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdemServico;
use App\Models\ValoresServicos;
use App\Models\OrdemServicoStatus;
use App\Models\OrdemServicoCusto;
use App\Models\OrdemServicoFuncionario;
use Carbon\Carbon;
use App\Helpers\ErrorResponse;

class OrdemServicoController extends Controller
{

    public function list(Request $request)
    {
        try {

            $ordem_servicos = $this->getDefaultList();

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

            if (!empty($body['funcionarios']) && count($body['funcionarios']) > 0) {
                $ordem_servicos->whereHas('funcionarios', function ($query) use ($body) {
                    $query->whereIn('funcionario.id', $body['funcionarios']);
                });
            }

            $ordem_servicos->orderBy('data', 'asc')->orderBy('hora', 'asc');
            $kanbanList = $this->getKanbanList($ordem_servicos);

            return response()->json([
                'kanban' => collect($kanbanList)->values(),
                'list' => $ordem_servicos->get()
            ]);
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

    public function finalizarOrdemServico(Request $request, $id)
    {
        try {

            $body = $request->all();
            $ordem_servico = OrdemServico::find($id);
            $valor_servico = ValoresServicos::where('cliente_id', $ordem_servico->cliente_id)
                                        ->where('tipo_servico_id', $ordem_servico->tipo_servico_id)
                                        ->first();
            
            $ordem_servico->valor = ($valor_servico->valor ?? 1) * (float)$body['quantidade_trabalho'];
            $ordem_servico->save();

            $ordem_servico_status = OrdemServicoStatus::create([
                'ordem_servico_id' => $id,
                'descricao' => 'finalizado'
            ]);

            return response()->json($valor_servico);

        } catch (\Throwable $e) {
            return response()->json(new ErrorResponse($e->getMessage()));
        }
    }

    public function deleteFuncionarioOrdemServico(Request $request, $ordem_servico_id)
    {
        try {

            $ordem_servico_func = OrdemServicoFuncionario::where('ordem_servico_id', $ordem_servico_id)
                ->whereIn('funcionario_id', $request->all()['funcionarios_id']);

            $ids = collect($ordem_servico_func->get())->map(fn ($f) => $f->id);
            OrdemServicoCusto::whereIn('ordem_servico_funcionario_id', $ids)->delete();
            $ordem_servico_func->delete();

            $ordem_servico = OrdemServico::with('funcionarios.pessoa')
                ->find($ordem_servico_id);

            return response()->json($ordem_servico->funcionarios);
        } catch (\Throwable $e) {
            return response()->json(new ErrorResponse($e->getMessage()));
        }
    }

    public function getValorServicoOS($id)
    {
        try {

            $ordem_servico = OrdemServico::find($id);
            $valor_servico = ValoresServicos::with('unidadeMedida')
                                        ->where('cliente_id', $ordem_servico->cliente_id)
                                        ->where('tipo_servico_id', $ordem_servico->tipo_servico_id)
                                        ->first();
            
            return response()->json($valor_servico);

        } catch (\Throwable $e) {
            return response()->json(new ErrorResponse($e->getMessage()));
        }
    }

    private function saveOrdemServico($data)
    {
        $ordem_servico = null;
        if (!isset($data['id'])) {

            $ordem_servico = OrdemServico::create($data);
            OrdemServicoStatus::create([
                'ordem_servico_id' => $ordem_servico->id,
                'descricao' => 'agendado'
            ]);

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

    private function getKanbanList($ordem_servicos)
    {
        $kanbanList = [];
        $ordem_servicos = empty($ordem_servicos) ? $this->getDefaultList() : $ordem_servicos;

        collect($ordem_servicos->get())->each(function ($order) use (&$kanbanList) {
            if (!empty($order->active_status) && $order->active_status->descricao != 'finalizado') {
                $date = Carbon::parse($order->data);
                $index = $date->day . '_' . $date->month . '_' . $date->year;
                $kanbanList[$index] = empty($kanbanList[$index]) ? ['id' => $index, 'titulo' => $date->format('d/m/Y'), 'cards' => []] : $kanbanList[$index];
                array_push($kanbanList[$index]['cards'], $order);
            }
        });
        return $kanbanList;
    }

    private function getDefaultList()
    {
        return OrdemServico::where('ativo', true)
            ->with('funcionarios.pessoa')
            ->with('custos');
    }
}
