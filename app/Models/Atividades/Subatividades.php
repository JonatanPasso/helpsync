<?php

namespace App\Models\Atividades;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Aulas
 *
 * @package App\Models\Beta\Subatividades
 * @mixin \Eloquent
 */
class Subatividades extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_subatividades';

    public $timestamps = false;

    public static function getAll()
    {
    	return self::all();
    }

    public static function getSubatividadePorIdAtividade($idAtividade)
    {
    	return self::whereIdAtividade($idAtividade)->get();
    }

    public static function getSubatividadePorId($idSubatividade)
    {
    	return self::where('id', $idSubatividade)->first();
    }

	public static function getSubAtividadePorAtividade($idAtividade)
	{
		return self::where('id_atividade', $idAtividade);
	}

	public static function excluirSubAtividade($request)
	{
		return self::whereId($request->id_subatividade)->delete();
	}
}

