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
        'cpf_cnpj',
        'ativo'
    ];

    public function ordemServicos()
    {
        return $this->hasMany(OrdemServico::class);
    }

    public function valoresServicos()
    {
        return $this->hasMany(ValoresServicos::class);
    }

    public function valoresFuncionarios()
    {
        return $this->hasMany(ValoresFuncionarios::class);
    }    

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

}
