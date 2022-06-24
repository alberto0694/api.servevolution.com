<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $table = 'pessoa';

    protected $fillable = [
        'id',
        'razao',
        'apelido',
        'nome',
        'foto',
        'contatoImediato',
        'telefone',
        'email',
        'ativo'
    ];

    protected $appends = [
        'normalized_name',
    ];

    public function getNormalizedNameAttribute()
    {
        return !empty($this->razao) ? $this->razao : $this->apelido;
    }

    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }

    public function colaboradores()
    {
        return $this->hasMany(Colaborador::class);
    }


}
