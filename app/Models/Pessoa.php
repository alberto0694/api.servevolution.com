<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $table = 'pessoa';

    protected $fillable = [
        'id',
        'razao',
        'apelido',
        'nome',
        'foto',
        'contatoImediato',
        'telefone',
        'email',
        'ativo'
    ];

}
