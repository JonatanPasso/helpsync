<?php

namespace App\Models\Estoque;

use App\Models\Geral\Clientes;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Estoque
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property int $cliente_id
 * @property int $nome
 * @property int $descricao
 * @property int $criado_por
 * @property int $criado_em
 *
 */
class Estoque extends Model
{
    protected $table = 'estoque_estoque';

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

    public function armazens()
    {
        return $this->hasMany(Armazem::class,
            'estoque_id', 'id');
    }

    public function cliente()
    {
        return $this->hasOne(Clientes::class,
            'id', 'cliente_id');
    }


}
