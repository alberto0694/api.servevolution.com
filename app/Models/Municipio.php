<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;

    protected $table = 'municipio';

    protected $fillable = [
        'id',
        'nome',
        'codigo_ibge',
        'estado_id',
        'ativo',
    ];

    public function estado()
    {
        return $this->belongsTo(Estado::class);
    }

}
