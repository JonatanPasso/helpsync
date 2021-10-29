<?php

namespace App\Models\Frota;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


/**
 * Class Eventos
 * @package App\Models\Rastreio
 *
 * @property int $id
 * @property int $tracker_log_id
 * @property Carbon $data_hora_processado
 * @property int $veiculo_id
 * @property String $evento
 *
 * @property Veiculos $veiculo Veículo associado ao evento
 * @property Logs $tracker_log Log rastreamento associado ao evento
 *
 *
 */
class HistoricoEventos extends Model
{
    protected $table = 'frota_historico_evento';


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

    public function evento()
    {
        return $this->hasOne(Eventos::class, 'id', 'evento_id');
    }

    public function trackerLog()
    {

        return $this->hasOne(Logs::class, 'id', 'tracker_log_id');
    }

}
