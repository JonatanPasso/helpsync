<?php

namespace App\Models\Frota;

use App\Models\Geral\Clientes;
use App\Models\Geral\UsuariosXclientes;
use Illuminate\Database\Eloquent\Model;


/**
 * Class Veiculos
 * @package App\Models\Frota
 *
 * @property int $id
 */
class Veiculos extends Model
{
    protected $table = 'frota_veiculos';

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


    public function tipo()
    {
        return $this->hasOne(TipoVeiculos::class, 'id', 'tipo_id');
    }

    public function marca()
    {
        return $this->hasOne(Marcas::class, 'id', 'marca_id');
    }

    public function modelo()
    {
        return $this->hasOne(Modelos::class, 'id', 'modelo_id');
    }


    public function clientes()
    {
        /*
         *   'App\Post',
            'App\User',
            'country_id', // Foreign key on users table...
            'user_id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'id' // Local key on users table...
         */
        return $this->hasManyThrough(
            Clientes::class,
            VeiculosXClientes::class,
            'veiculo_id',
            'id',
            'id', 'cliente_id');
    }

    public function veiculosXClientes()
    {
        return $this->hasMany(VeiculosXClientes::class,
            'veiculo_id', 'id');
    }

    public function rastreador()
    {

        return $this->hasOne(Rastreadores::class, 'esn', 'rastreador_esn');
    }

    public function eventos()
    {
        return $this->hasMany(Eventos::class, 'veiculo_id', 'id');
    }



//    public function lastLogs()
//    {
//        return $this->hasOne(LastLog::class, 'rastreador_esn', 'rastreador_esn');
//    }


}
