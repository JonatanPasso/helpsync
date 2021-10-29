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
class ChamadosModerados extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_chamados_moderados';

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function usuarioModerado()
    {
        return $this->hasOne(Usuarios::class, 'id', 'id_usuario_moderado');
    }

}

