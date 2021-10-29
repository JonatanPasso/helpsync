<?php

namespace App\Models\Frota;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Logs
 * @package App\Models\Rastreio
 *
 * @property int $id
 */
class TipoVeiculos extends Model
{
    protected $table = 'frota_tipo_veiculos';

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
