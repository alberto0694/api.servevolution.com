<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Funcionario;
use App\Models\OrdemServicoFuncionario;
use App\Models\OrdemServicoCusto;
use App\Models\TipoServico;
use App\Models\Cliente;

class OrdemServico extends Model
{
    use HasFactory;

    protected $table = 'ordem_servico';

    protected $fillable = [
        'id',
        'data',
        'hora',
        'descricao',
        'status',
        'tipo_servico_id',
        'cliente_id',
        'ativo'
    ];

    protected $appends = ['titulo'];

    public function getTituloAttribute()
    {
        return ($this->cliente->pessoa->razao ?? $this->cliente->pessoa->apelido) . " #" . $this->servico?->descricao;
    }

    public function funcionarios()
    {
        return $this->belongsToMany(Funcionario::class, OrdemServicoFuncionario::class);
    }

    public function custos()
    {
        return $this->hasManyThrough(OrdemServicoCusto::class, OrdemServicoFuncionario::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function servico()
    {
        return $this->belongsTo(TipoServico::class, 'tipo_servico_id', 'id');
    }


}
