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

}
