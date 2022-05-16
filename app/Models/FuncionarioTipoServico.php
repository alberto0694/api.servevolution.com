<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FuncionarioTipoServico extends Model
{
    use HasFactory;

    protected $table = 'funcionario_tipo_servico';

    protected $fillable = [
        'id',
        'funcionario_id',
        'tipo_servico_id'
    ];

}
