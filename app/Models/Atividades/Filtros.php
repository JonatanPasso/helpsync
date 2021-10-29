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
class Filtros extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_filtros';

    public $timestamps = false;

	public static function getAllFiltros($request)
	{
		$filtros = \DB::table('atividades_filtros as f')
			->join('atividades_filtros_usuarios as fu', 'fu.id_filtro', '=', 'f.id')
			->where('fu.id_usuario', $request->idUsuario)
			->where('fu.status_filtro','=', 'A');

		return $filtros->get([
			'f.id as id_principal',
			'f.titulo_filtro',
			'f.descricao_filtro',
			'f.parametro',
			'f.icon',
			'f.class_coluna',
			'fu.id_filtro as id_filtro_usuario',
			'fu.id_usuario',
			'fu.status_filtro'
		]);

	}

	/** Relacionamentos */
	public function filtrosUsuarios()
	{
		return $this->hasOne(FiltrosUsuarios::class, 'id_filtro', 'id');
	}
}

