<?php

namespace App\Models\Comercial;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Eventos
 * @package App\Models\Rastreio
 *
 * @property int $id
 *
 *
 *
 */
class CabecalhoNota extends Model
{
    protected $table = 'comercial_cabecalho_nota';


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
