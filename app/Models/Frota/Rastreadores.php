<?php

namespace App\Models\Frota;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Logs
 * @package App\Models\Rastreio
 *
 * @property int $id
 */
class Rastreadores extends Model
{
    protected $table = 'frota_rastreadores';

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

    public function veiculo()
    {
        return $this->hasOne(Veiculos::class, 'rastreador_esn', 'esn');
    }

    public function logs()
    {
        return $this->hasMany(Logs::class, 'rastreador_esn', 'esn');
    }


}
