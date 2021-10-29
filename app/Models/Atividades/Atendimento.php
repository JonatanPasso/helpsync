<?php

namespace App\Models\Atividades;

use App\Evpar\HasCompositePrimaryKey;
use App\Models\Geral\Departamentos;
use App\Models\Geral\Usuarios;
use App\Models\Geral\VagaDepartamento;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\Atividades\Subatividades;

/**
 * Class Aulas
 *
 * @package App\Models\Atividades
 * @mixin \Eloquent
 */
class Atendimento extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_chamados_atendimento';

    public $timestamps = false;


    public static function hasAtendimento($request)
    {
    	return self::query()
	        ->whereIdChamado($request->id_chamado)
		    ->whereIdUsuarioChamado($request->id_usuario_chamado)
            ->with('chamadosCompartilhadosAtualizar')
		    ->get();
    }

    public static function listaUsuariosCompartilhados($idChamado)
    {
    	return self::whereIdChamado($idChamado)->get();
    }

    public static function verifyUserAttendence($request)
    {
    	return self::query()
		    ->whereIdChamado($request->id_chamado)
		    ->whereIdUsuarioChamado($request->id_usuario_chamado)
		    ->first();
    }

    public function chamadosCompartilhadosAtualizar()
    {
        return $this->hasMany(Compartilhados::class, 'id_chamado', 'id_chamado');
    }

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasOne
	 */
    public function usuario()
    {
    	return $this->hasOne(Usuarios::class, 'id', 'id_usuario_chamado');
    }

}

