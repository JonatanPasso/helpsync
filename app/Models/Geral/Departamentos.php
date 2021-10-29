<?php

namespace App\Models\Geral;

use App\Models\Sca\T0001;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Acessos
 *
 * @package App\Models\Beta\Everyday
 * @mixin \Eloquent
 * @property int $id
 * @property int $empresa_id
 * @property String $nome
 * @property int|null $departamento_pai_id
 */
class Departamentos extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'geral_departamentos';

    public $timestamps = false;

    public $appends = ['filhos'];

    public function filhos()
    {
        return $this->hasMany(Departamentos::class, 'departamento_pai_id', 'id');
    }

    public function getFilhosAttribute($value)
    {
        return $this->filhos()->get();
    }

    public static function montarHierarquia($empresa_id)
    {

        $raiz = Departamentos::whereNull('departamento_pai_id')
            ->where('empresa_id', $empresa_id)
            ->first();

        dump($raiz->toArray());

    }

    public function vagasDepartamento()
    {
        return $this->hasMany(VagaDepartamento::class, 'departamento_id', 'id');
    }

    public function listarUsuariosVinculados()
    {
        $vagaDepartamentos = $this
            ->vagasDepartamento
            ->filter(function ($item) {
                if ($item->usuario) return true;
                return false;
            })
            ->transform(function ($item) {
                if ($item->usuario) return $item->usuario;
            });

        return $vagaDepartamentos;
    }

    public function empresa()
    {
        return $this->hasOne(Empresas::class, 'id', 'empresa_id');
    }

    public static function getDepartamentosPorEmpresa($request)
    {
    	return self::where('empresa_id', $request->idEmpresa);
    }

    public static function getDepartamentoPorId($idDepartamento)
    {
    	return self::where('id', $idDepartamento)->first();
    }
}