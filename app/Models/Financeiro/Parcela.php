<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    use HasFactory;

    protected $table = 'parcela';

    protected $fillable = [
        'id',
        'titulo_id',
        'valor_nominal',
        'valor_atualizado',
        'valor_baixado',
        'saldo',
        'vencimento',
        'ativo'
    ];

}
