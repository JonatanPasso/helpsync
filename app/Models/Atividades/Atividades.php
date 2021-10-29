<?php

namespace App\Models\Atividades;

use App\Models\Geral\Departamentos;
use App\Models\Geral\Usuarios;
use App\Models\Geral\VagaDepartamento;
use Illuminate\Database\Eloquent\Model;
use App\Models\Atividades\Subatividades;
use Illuminate\Support\Facades\Auth;

/**
 * Class Aulas
 *
 * @package App\Models\Atividades
 * @mixin \Eloquent
 */
class Atividades extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_atividades';

    public $timestamps = false;

    public static function getAtividadesPorUsuario($arrayDepartamento)
    {
        $parametro = join(",", $arrayDepartamento);

        $query = \DB::select("SELECT id,
                                    nm_atividade,
                                    moderada,
                                    privada,
                                    (SELECT COUNT(atividades_subatividades.id_atividade)
                                     FROM atividades_subatividades
                                     WHERE atividades_subatividades.id_atividade = atividades_atividades.id) as somaSub
                            FROM atividades_atividades
                            WHERE atividades_atividades.id_departamento IN ($parametro)");
        return $query;
    }

    public static function atividadesPermitidas($idDepartamento)
    {
        $betaUser = Usuarios::whereId(Auth::user()->id)->firstOrFail();
        $dep = VagaDepartamento::where('usuario_id', $betaUser->id)
            ->with('departamento')
            ->pluck('departamento_id');

        $query = self::query();

        if (!in_array($idDepartamento, (array)$dep)) {
            $query->where('privada', '!=', 'true');
        }
        return $query->where('id_departamento', $idDepartamento)->get();
    }

    public static function getAtividadePorId($idAtividade)
    {
        return self::whereId($idAtividade)->first();
    }

    public static function getAtividadePorDepartamento($idDepartamento)
    {
        return self::whereIdDepartamento($idDepartamento);
    }

    public static function consultaAtividadePorId($request)
    {
        return self::query()
            ->whereId($request->id_atividade)
            ->first();
    }

    public static function excluirAtividade($request)
    {
        return self::whereId($request->id_atividade)->delete();
    }

    public function departamento()
    {
        return $this->hasOne(Departamentos::class, 'id', 'id_departamento');
    }

    public function subatividades()
    {
        return $this->hasMany(Subatividades::class, 'id_atividade', 'id');
    }
}

