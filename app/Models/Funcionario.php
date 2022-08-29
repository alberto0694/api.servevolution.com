<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $table = 'funcionario';

    protected $fillable = [
        'id',
        'pessoa_id',
        'cpf',
        'rg',
        'orgao_emissor',
        'uf_emissor',
        'sexo',
        'data_admissao',
        'data_demissao',
        'referencia_id',
        'ativo'
    ];

    public function tipoServicos()
    {
        return $this->belongsToMany(TipoServico::class, FuncionarioTipoServico::class);
    }

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class, 'pessoa_id');
    }
}
