<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Titulo extends Model
{
    use HasFactory;

    protected $table = 'titulo';

    protected $fillable = [
        'id',
        'parcela_id',
        'valor_baixado',
        'ativo'
    ];

}