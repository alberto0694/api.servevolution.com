<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'cliente';

    protected $fillable = [
        'id',
        'pessoa_id',
        'senha',
        'ativo'
    ];

    public function ordemServicos()
    {
        return $this->hasManyThrough(OrdemServico::class, OrdemServicoCliente::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

}
