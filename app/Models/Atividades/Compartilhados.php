<?php

namespace App\Models\Atividades;

use App\Evpar\HasCompositePrimaryKey;
use App\Models\Geral\Departamentos;
use App\Models\Geral\Usuarios;
use App\Models\Geral\VagaDepartamento;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Aulas
 *
 * @package App\Models\Atividades
 * @mixin \Eloquent
 */
class Compartilhados extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_chamados_compartilhados';

    public $timestamps = false;


    public static function listaUsuariosCompartilhados($idChamado)
    {
    	return self::whereIdChamado($idChamado)->get();
    }


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
    public function usuario()
    {
    	return $this->hasOne(Usuarios::class, 'id', 'id_usuario_compartilhado');
    }

    public function departamentos()
    {
        return $this->hasOne(Departamentos::class, 'id', 'departamento_id');
    }


}

