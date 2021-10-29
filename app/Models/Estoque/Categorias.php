<?php

namespace App\Models\Estoque;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Categorias
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property String nome
 * @property String descricao
 * @property String imagem
 */
class Categorias extends Model
{
    protected $table = 'estoque_categorias';

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


}
