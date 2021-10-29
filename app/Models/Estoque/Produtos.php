<?php

namespace App\Models\Estoque;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Produtos
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property String nome
 * @property String ncm
 * @property int fabricante_id
 * @property int modelo_id
 * @property int categoria_id
 * @property String unidade
 * @property String unidade_venda
 * @property String unidade_compra
 * @property String imagem
 * @property int marca_id
 * @property String codigo_referencia
 * @property String tipo_cadastro
 * @property Carbon cadastrado_por
 * @property Carbon cadastrado_em
 */
class Produtos extends Model
{
    protected $table = 'estoque_produtos';

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

    public function fabricante()
    {
        return $this->hasOne(Fabricantes::class, 'id',
            'fabricante_id');
    }

    public function modelo()
    {
        return $this->hasOne(Modelos::class, 'id',
            'modelo_id');
    }

    public function categoria()
    {
        return $this->hasOne(Categorias::class, 'id',
            'categoria_id');
    }

    public function marca()
    {
        return $this->hasOne(Marcas::class, 'id',
            'marca_id');
    }

    public function unidade()
    {
        return $this->hasOne(Unidades::class, 'id', 'unidade_id');
    }

    public function linha()
    {
        return $this->hasOne(Linhas::class, 'id',
            'linha_id');
    }

    public function codigosReferencia()
    {
        return $this->hasMany(CodigosReferencia::class,
            'produto_id', 'id');
    }

    public function movimentoItem()
    {
        return $this->hasMany(MovimentoItem::class, 'produto_id', 'id');
    }

}
