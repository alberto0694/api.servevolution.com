<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;

    protected $table = 'parcela';

    protected $fillable = [
        'id',
        'valor_nominal',
        'valor_atualizado',
        'valor_baixado',
        'saldo',
        'titulo_id',        
        'vencimento',
        'ativo'
    ];

}
