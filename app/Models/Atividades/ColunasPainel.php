<?php

namespace App\Models\Atividades;

use App\Models\Beta\Everyday\Departamentos;
use App\Models\Beta\Everyday\Usuarios;
use App\Models\Beta\Everyday\VagaDepartamento;
use Illuminate\Database\Eloquent\Model;
use App\Models\Beta\Atividades\Subatividades;

/**
 * Class Aulas
 *
 * @package App\Models\Atividades
 * @mixin \Eloquent
 */
class ColunasPainel extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_colunas_painel';

    public $timestamps = false;

    public static function getAllColunas($request)
    {
	    $filtros = \DB::table('atividades_colunas_painel as p')
		    ->join('atividades_colunas_painel_usuarios as pu', 'pu.id_coluna_painel', '=', 'p.id')
		    ->where('pu.id_usuario', $request->idUsuario);
//		    ->where('pu.status','=', 'A');

	    return $filtros->get([
		    'p.id as id_coluna',
		    'p.titulo_coluna',
		    'p.posicao_coluna',
		    'pu.id_coluna_painel as id_coluna_painel_usuario',
		    'pu.id_usuario',
		    'pu.status',
	    ]);
    }

}

