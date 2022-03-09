<?php

namespace App\Models\Permissao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilPapel extends Model
{
    use HasFactory;

    protected $table = 'perfil_papel';

    protected $fillable = [
        'id',
        'perfil_id',
        'papel_id'
    ];

}
