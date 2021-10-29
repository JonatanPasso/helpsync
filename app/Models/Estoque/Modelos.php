<?php

namespace App\Models\Estoque;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Modelos
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property String nome
 * @property int marca_id
 */
class Modelos extends Model
{
    protected $table = 'estoque_modelos';

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

    public function marca()
    {
        return $this->hasOne(Marcas::class, 'id', 'marca_id');
    }


}
