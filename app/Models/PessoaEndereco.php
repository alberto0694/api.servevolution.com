<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PessoaEndereco extends Model
{
    use HasFactory;

    protected $table = 'pessoa_endereco';

    protected $fillable = [
        'id',
        'pessoa_id',
        'endereco_id'
    ];


}
