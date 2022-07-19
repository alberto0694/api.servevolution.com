<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValoresServicos extends Model
{
    use HasFactory;

    protected $table = 'valores_servicos';

    protected $fillable = [
        'id',
        'tipo_servico_id',
        'cliente_id',
        'unidade_medida_id',
        'valor',
        'ativo'
    ];

    public function unidadeMedida()
    {
        return $this->belongsTo(UnidadeMedida::class);
    }

    public function tipoServico()
    {
        return $this->belongsTo(TipoServico::class);
    }    

}
