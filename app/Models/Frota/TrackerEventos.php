<?php

namespace App\Models\Frota;

use Illuminate\Database\Eloquent\Model;


/**
 * Class TrackerEventos
 * @package App\Models\Frota
 *
 * @property int id Id evento
 * @property int veiculo_id Id Veículo
 * @property String evento EVENTO
 * @property int log_id Id log posicao
 * @property int gps_timestamp Timestamp do evento
 * @property int processado_em Timestamp do processamento
 * @property Veiculos veiculo Veiculo
 * @property Logs log Log
 */
class TrackerEventos extends Model
{
    protected $table = 'frota_tracker_eventos';

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

    public const VEICULO_DESLIGADO = 'VEICULO_DESLIGADO';
    public const VEICULO_LIGADO = 'VEICULO_LIGADO';


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function log()
    {
        return $this->hasOne(Logs::class, 'id', 'log_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function veiculo()
    {
        return $this->hasOne(Veiculos::class, 'id', 'veiculo_id');
    }

}
