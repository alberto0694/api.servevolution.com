<?php

namespace App\Models\Permissao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PapelUsuario extends Model
{
    use HasFactory;

    protected $table = 'papel_usuario';

    protected $fillable = [
        'id',
        'papel_id',
        'usuario_id'
    ];

}
