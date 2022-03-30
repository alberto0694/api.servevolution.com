<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdemServicoTipo extends Model
{
    use HasFactory;

    protected $table = 'ordem_servico_tipo';

    protected $fillable = [
        'id',
        'ordem_servico_id',
        'tipo_servico_id'
    ];

}
