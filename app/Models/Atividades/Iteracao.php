<?php

namespace App\Models\Atividades;

use App\Evpar\HasCompositePrimaryKey;
use App\Models\Geral\Departamentos;
use App\Models\Geral\FileStorage;
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
class Iteracao extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'atividades_iteracao_chamado';

    public $timestamps = false;

    public static function getAllIteracoes($idChamado)
    {
    	return self::query()
		    ->whereIdChamado($idChamado)
		    ->orderBy('criado_em', 'desc')
		    ->with('usuarioCriadorIteracao')
		    ->with('chamadosInterados')
		    ->with('chamadosAtendimento.usuario')
            ->with('chamadosCompartilhados.usuario')
            ->with(['chamadosCompartilhados.departamentos' => function($q){
                $q->groupBy('id',
                            'empresa_id',
                            'nome',
                            'departamento_pai_id',
                            'url_img_capa',
                            'url_img_logo');
            }])
            ->with('chamadosModerados.usuarioModerado')
		    ->with('anexoIteracao')
		    ->with('anexoPorChamado')
            ->with(['anexoChamadoPorIteracao' => function($query){
                $query->where('iteracao_id', '<>', null);
            }])
		    ->get()
            ->groupBy(function ($item){
                return Carbon::parse($item->criado_em)->format('Ymd');
            });
    }

	public static function getHours($idChamado)
	{
		return self::query()
			->whereIdChamado($idChamado)
			->with('chamadosInterados')
			->get();
	}

	public static function getUltima($idChamado)
	{
		return self::whereIdChamado($idChamado)->get();
	}

	public static function countAnexos()
	{
		return self::where('uid_anexo', '<>', '')->count();
	}

	/**
	 * Relacionamentos
	 */
	public function usuarioCriadorIteracao()
	{
		return $this->hasOne(Usuarios::class, 'id', 'criado_por');
	}

	public function chamadosInterados()
	{
		return $this->hasOne(Chamados::class, 'id', 'id_chamado');
	}

	public function chamadosAtendimento()
	{
		return $this->hasMany(Atendimento::class, 'id_chamado', 'id_chamado');
	}

	public function chamadosCompartilhados()
    {
        return $this->hasMany(Compartilhados::class, 'id_iteracao_chamado', 'id');
    }

    public function chamadosModerados()
    {
        return $this->hasOne(ChamadosModerados::class, 'id_iteracao', 'id');
    }

	public function anexoIteracao()
	{
		return $this->hasOne(FileStorage::class, 'uid', 'uid_anexo');
	}

    public function anexoChamadoPorIteracao()
    {
        return $this->hasMany(ChamadosAnexos::class, 'chamado_id', 'id_chamado');
    }

    public function anexoPorChamado()
    {
        return $this->hasMany(ChamadosAnexos::class, 'chamado_id', 'id_chamado');
    }

}

