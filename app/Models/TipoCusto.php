<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCusto extends Model
{
    use HasFactory;

    protected $table = 'tipo_custo';

    protected $fillable = [
        'id',
        'descricao',
        'ativo'
    ];

}
