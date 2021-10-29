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
class ColunasPainelUsuarios extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_colunas_painel_usuarios';

    public $timestamps = false;

	public static function consultaColunasUsuarioPorId($request)
	{
		return self::query()
			->whereIdColunaPainel($request->idColuna)
			->whereIdUsuario($request->idUsuario)
			->first();
	}
}

