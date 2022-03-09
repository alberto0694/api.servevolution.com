<?php

namespace App\Models\Permissao;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menu';

    protected $fillable = [
        'titulo',
        'nivel',
        'icone',
        'icone_aux',
        'rota',
        'papel_id',
        'menu_pai_id',
        'excluido',
    ];

}
