<?php

namespace App\Models\Permissao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permissao extends Model
{
    use HasFactory;

    protected $table = 'permissao';

    protected $fillable = [
        'programa',
        'titulo',
        'descricao',
        'ativo'
    ];

}
