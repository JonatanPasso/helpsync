<?php

namespace App\Models\Atividades;

use App\Models\Geral\Departamentos;
use App\Models\Geral\Usuarios;
use App\Models\Geral\VagaDepartamento;
use Illuminate\Database\Eloquent\Model;
use App\Models\Atividades\Subatividades;

/**
 * Class Aulas
 *
 * @package App\Models\Atividades
 * @mixin \Eloquent
 */
class FiltrosUsuarios extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_filtros_usuarios';

    public $timestamps = false;

	public static function getAllFiltros($request)
	{
		return self::query()
//			->where('status_filtro', '=', 'A')
			->where('id_usuario', $request->idUsuario)
			->with('filtros')
			->get();
	}

	public static function consultaFiltroUsuarioPorId($request)
	{
		return self::query()
			->whereIdFiltro($request->idFiltro)
			->whereIdUsuario($request->idUsuario)
			->first();
	}

	/** Relacionamentos */
	public function filtros()
	{
		return $this->belongsToMany(Filtros::class, 'id', 'id_filtro');
	}
}

