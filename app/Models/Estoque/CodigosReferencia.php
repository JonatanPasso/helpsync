<?php

namespace App\Models\Estoque;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


/**
 * Class CodigosReferencia
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property int produto_id Id do produto
 * @property String codigo codigo de referencia
 * @property Marcas nome Nome do codigo de referencia
 * @property Produtos $produto Produtos associado ao codigo
 */
class CodigosReferencia extends Model
{
    protected $table = 'estoque_codigos_referencia';

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
     * Produto associado ao codigo
     * @return HasOne
     */
    public function produtos()
    {
        return $this->hasOne(Produtos::class, 'id',
            'produto_id');
    }

}
