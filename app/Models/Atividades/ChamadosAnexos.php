<?php

namespace App\Models\Atividades;

use App\Models\Geral\Usuarios;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Aulas
 *
 * @package App\Models\Atividades
 * @mixin \Eloquent
 */
class ChamadosAnexos extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_chamados_anexos';

    public $timestamps = false;

    public function usuariosIteracao()
    {
        return $this->hasOne(Usuarios::class, 'id', 'criado_por');
    }
}

