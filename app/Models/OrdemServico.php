<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServico extends Model
{
    use HasFactory;

    protected $table = 'ordem_servico';

    protected $fillable = [
        'id',
        'titulo',
        'descricao',
        'ativo'
    ];


    public function servicos()
    {
        return $this->hasManyThrough(TipoServico::class, OrdemServicoTipo::class);
    }

    public function clientes()
    {
        return $this->hasManyThrough(TipoServico::class, OrdemServicoTipo::class);
    }

}
