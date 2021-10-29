<?php

namespace App\Models\Estoque;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Marcas
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property String nome
 * @property int fabricante_id
 */
class Marcas extends Model
{
    protected $table = 'estoque_marcas';

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


}
