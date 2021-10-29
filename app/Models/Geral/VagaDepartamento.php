<?php

namespace App\Models\Geral;

use App\Models\Beta\Atividades\Chamados;
use App\Models\Totvs\Servico;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class VagaDepartamento
 *
 * @package App\Models\Beta\Everyday
 * @mixin \Eloquent
 * @property int $id
 * @property int $empresa_id
 * @property String $nome
 * @property String $descricao
 */
class VagaDepartamento extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'geral_vaga_departamento';

    public $timestamps = false;

    public function departamento()
    {
        return $this->hasOne(Departamentos::class, 'id', 'departamento_id');
    }

    public function vaga()
    {
        return $this->hasOne(Vagas::class, 'id', 'vaga_id');
    }

    public function vagaDepartamentoServico()
    {
        return $this->hasMany(VagaDepartamentoServico::class,
            'vaga_departamento_id',
            'id');
    }

    public function acessos()
    {
        return $this->hasMany(Acessos::class, 'vaga_departamento_id', 'id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuarios::class, 'id', 'usuario_id');
    }

    public function chamadoUser(){
    	return $this->hasMany(Chamados::class, 'id_usuario_executor', 'usuario_id');
    }

    public function filhos()
    {
        return $this->hasMany(VagaDepartamento::class, 'vaga_departamento_pai', 'id')
            ->with('vaga')
            ->with('departamento')
            ->with('usuario')
            ->with('filhos');

        /*return VagaDepartamento::whereDepartamentoPai($this->id)
            ->whereDepartamentoId($this->departamento_id)
            ->with('filhos')
            ->get();*/
    }

}
