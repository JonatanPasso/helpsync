<?php

namespace App\Models\Estoque;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Fabricantes
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property String nome
 */
class Fabricantes extends Model
{
    protected $table = 'estoque_fabricantes';

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
