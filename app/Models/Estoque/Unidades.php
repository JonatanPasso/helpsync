<?php

namespace App\Models\Estoque;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;


/**
 * Class Medidas
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property String unidade Unidade de medida
 * @property String descricao Descricao da unidade de medida
 * @property HasMany | Collection produtos Produtos com esta unidade de medida
 * @property HasMany | Collection produtosCompra Produtos com esta unidade de medida
 * @property HasMany | Collection produtosVenda Produtos com esta unidade de medida
 */
class Unidades extends Model
{
    protected $table = 'estoque_unidades';

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

    public function produtos()
    {
        return $this->hasMany(Produtos::class, 'unidade_id', 'id');
    }


    public function produtosCompra()
    {
        return $this->hasMany(Produtos::class, 'unidade_venda_id', 'id');
    }


    public function produtosVenda()
    {
        return $this->hasMany(Produtos::class, 'unidade_compra_id', 'id');
    }
}
