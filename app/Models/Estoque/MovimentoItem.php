<?php

namespace App\Models\Estoque;

use App\Models\Geral\Usuarios;
use Illuminate\Database\Eloquent\Model;


/**
 * Class MovimentoItem
 * @package App\Models\Estoque
 *
 * @property int $id Código item do moviemento
 * @property int $armazem_id Código do armazem
 * @property String $tipo Tipo de movimentoacao do item
 * @property int $produto_id Codigo do produto
 * @property int $realizado_por Id do usuario responsavel pelo movimento
 * @property int $realizado_em Data da alteracao do movimento
 * @property int $quantidade Quantidade
 * @property int $unidade Uniade
 */
class MovimentoItem extends Model
{
    protected $table = 'estoque_movimento_item';

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

    /**
     * Estoque relacionado
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function armazem()
    {
        return $this->hasOne(Estoque::class, 'id',
            'estoque_id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function movimento()
    {
        return $this->hasOne(Movimento::class, 'id',
            'estoque_id');
    }

    public function realizadoPor()
    {
        return $this->hasOne(Usuarios::class, 'id', 'realizado_por');
    }

    public function produto()
    {
        return $this->hasOne(Produtos::class, 'id', 'produto_id');
    }


    public static function saldo($produto_id, $armazem_id)
    {
        $qtdProdutosEntrada = MovimentoItem::query()
            ->where('produto_id', $produto_id)
            ->where('armazem_id', $armazem_id)
            ->where('operacao', 'ENTRADA')
            ->sum('quantidade');

        $qtdProdutosSaida = MovimentoItem::query()
            ->where('produto_id', $produto_id)
            ->where('armazem_id', $armazem_id)
            ->where('operacao', 'SAIDA')
            ->sum('quantidade');

        return $qtdProdutosEntrada - $qtdProdutosSaida;
    }
}
