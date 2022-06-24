<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrdemServicoFuncionario;


class OrdemServicoCusto extends Model
{
    use HasFactory;

    protected $table = 'ordem_servico_custo';

    protected $fillable = [
        'ordem_servico_funcionario_id',
        'tipo_custo_id',
        'valor',
        'ativo'
    ];

    protected $casts = [ 'valor' => 'float' ];

    public function ordemServicoFuncionario()
    {
        return $this->belongsTo(OrdemServicoFuncionario::class, 'ordem_servico_funcionario_id', 'id', 'ordem_servico_funcionario');
    }

}
