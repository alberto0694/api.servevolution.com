<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Colaborador extends Model
{
    use HasFactory;

    protected $table = 'colaborador';

    protected $fillable = [
        'id',
        'pessoa_id',
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
