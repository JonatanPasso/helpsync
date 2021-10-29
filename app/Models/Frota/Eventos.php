<?php

namespace App\Models\Frota;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Eventos
 * @package App\Models\Rastreio
 *
 * @property int $id
 * @property String evento evento
 * @property int vel_min  velocidade minima
 * @property int vel_max velociade maxima
 * @property int tempo tempo ligado parado
 * @property int valor_km valor proximo alerta
 *
 *
 *
 */
class Eventos extends Model
{
    protected $table = 'frota_eventos';

    public const VELOCIDADE = 'VELOCIDADE';
    public const IGNICAO = 'IGNICAO';
    public const LIGADO_E_PARADO = 'LIGADO E PARADO';
    public const KM = 'KM';


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
        return $this->hasOne(Veiculos::class, 'id', 'veiculo_id');
    }

}
