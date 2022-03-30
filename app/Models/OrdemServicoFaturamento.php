<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServicoFaturamento extends Model
{
    use HasFactory;

    protected $table = 'ordem_servico_faturamento';

    protected $fillable = [
        'id',
        'ativo'
    ];

}
