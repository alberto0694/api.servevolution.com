<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCustoServico extends Model
{
    use HasFactory;

    protected $table = 'tipo_custo_servico';

    protected $fillable = [
        'id',
        'descricao',
        'ativo'
    ];

}
