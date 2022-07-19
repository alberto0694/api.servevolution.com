<?php

namespace App\Models\Financeiro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrdemServico;

class Titulo extends Model
{
    use HasFactory;

    protected $table = 'titulo';

    protected $fillable = [
        'id',
        'valor_nominal',
        'valor_atualizado',
        'valor_baixado',
        'saldo',
        'ativo'
    ];

    public function ordemServicos()
    {
        return $this->hasMany(OrdemServico::class);
    }

}
