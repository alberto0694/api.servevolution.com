<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Funcionario;
use App\Models\OrdemServicoFuncionario;

class OrdemServico extends Model
{
    use HasFactory;

    protected $table = 'ordem_servico';

    protected $fillable = [
        'id',
        'titulo',
        'descricao',
        'tipo_servico_id',
        'cliente_id',
        'ativo'
    ];

    public function funcionarios()
    {
        return $this->belongsToMany(Funcionario::class, OrdemServicoFuncionario::class);
    }

}
