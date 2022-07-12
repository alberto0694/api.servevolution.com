<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServicoStatus extends Model
{
    use HasFactory;

    protected $table = 'ordem_servico_status';

    protected $fillable = [
        'ordem_servico_id',
        'descricao',
        'ativo'
    ];

}
