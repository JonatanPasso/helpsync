<?php

namespace App\Models\Frota;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Logs
 * @package App\Models\Rastreio
 *
 * @property int $id
 */
class Marcas extends Model
{
    protected $table = 'frota_marcas';

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
