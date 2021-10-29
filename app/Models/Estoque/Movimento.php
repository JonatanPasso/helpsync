<?php

namespace App\Models\Estoque;

use App\Models\Geral\Clientes;
use App\Models\Geral\FileStorage;
use App\Models\Geral\Usuarios;
use Carbon\Carbon;
use Illuminate\Cache\FileStore;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Movimento
 * @package App\Models\Estoque
 *
 * @property int $id Codigo movimento
 * @property int $armazem_id Id do armazem
 * @property String $tipo Tipo de Movimento
 * @property String $doc_upload_uid Upload uid do documento
 * @property String $notas Notas do movimento
 * @property int $realizado_por Id usuario responsavel movimento
 * @property Carbon $realizado_em Data realizada do movimento
 */
class Movimento extends Model
{
    protected $table = 'estoque_movimento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public $timestamps = false;

    public function cliente()
    {
        return $this->hasOne(Clientes::class, 'id', 'cliente_id');
    }

    /**
     * Armazem relacionado
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function armazem()
    {
        return $this->hasOne(Armazem::class, 'id',
            'armazem_id');
    }

    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'id',
            'estoque_id');
    }

    public function realizadoPor()
    {
        return $this->hasOne(Usuarios::class, 'id',
            'realizado_por');
    }

    public function documento()
    {
        return $this->hasOne(FileStorage::class, 'uid', 'doc_upload_uid');
    }

    public function itens()
    {
        return $this->hasMany(MovimentoItem::class, 'movimento_id', 'id');
    }

    public function origemEmpresa()
    {
        return $this->hasOne(Clientes::class, 'id', 'origem_empresa_id');
    }

    public function origemEstoque()
    {
        return $this->hasOne(Estoque::class, 'id', 'origem_estoque_id');
    }

    public function origemArmazem()
    {
        return $this->hasOne(Armazem::class, 'id', 'origem_armazem_id');
    }
}
