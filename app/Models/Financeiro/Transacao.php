<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transacao extends Model
{
    use HasFactory;

    protected $table = 'transacao';

    protected $fillable = [
        'id',
        'parcela_id',
        'valor_baixado',
        'ativo'
    ];

}
