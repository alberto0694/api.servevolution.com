<?php

namespace App\Models\Permissao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Papel extends Model
{
    use HasFactory;

    protected $table = 'papel';

    protected $fillable = [
        'id',
        'permissao_id',
        'acao',
        'descricao'
    ];

}
