<?php

namespace App\Models\Estoque;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Armazem
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property int $estoque_id
 * @property String nome
 * @property Carbon criado_em
 * @property int criado_por
 */
class Armazem extends Model
{
    protected $table = 'estoque_armazem';

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
    public function estoque()
    {
        return $this->hasOne(Estoque::class, 'id',
            'estoque_id');
    }

}
