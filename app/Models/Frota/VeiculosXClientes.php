<?php

namespace App\Models\Frota;

use App\Models\Geral\Clientes;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Veiculos
 * @package App\Models\Frota
 *
 * @property int $id
 */
class VeiculosXClientes extends Model
{
    protected $table = 'frota_veiculos_clientes';

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


    public function cliente()
    {
        return $this->hasOne(Clientes::class, 'id', 'cliente_id');
    }

    public function veiculo()
    {
        return $this->hasOne(Veiculos::class, 'id', 'veiculo_id');
    }

}
