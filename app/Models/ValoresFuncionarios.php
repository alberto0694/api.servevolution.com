<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValoresFuncionarios extends Model
{
    use HasFactory;

    protected $table = 'valores_funcionarios';

    protected $fillable = [
        'id',
        'tipo_servico_id',
        'cliente_id',
        'funcionario_id',
        'unidade_medida_id',
        'valor',
        'ativo'
    ];

    protected $casts = [
        'valor' => 'float',
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function unidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class);
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function tipoServico()
    {
        return $this->belongsTo(TipoServico::class);
    }

}
