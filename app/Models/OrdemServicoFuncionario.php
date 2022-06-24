<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class OrdemServicoFuncionario extends Model
{
    use HasFactory;

    protected $table = 'ordem_servico_funcionario';

    protected $fillable = [
        'id',
        'funcionario_id',
        'ordem_servico_id',
        'valor_servico'
    ];

    public function custos()
    {
        return $this->hasMany(OrdemServicoCusto::class);
    }

}
