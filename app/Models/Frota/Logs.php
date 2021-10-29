<?php

namespace App\Models\Frota;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Logs
 * @package App\Models\Rastreio
 *
 * @property int $rastreador_esn
 * @property int $veiculo_id
 * @property int $motorista_id
 * @property String $gps_timestamp
 * @property String $server_timestamp
 * @property String $gps_lat
 * @property String $gps_lng
 * @property String $gps_dir
 * @property String $gps_fixo
 * @property String $gps_quality
 * @property String $gps_online
 * @property String $bateria_volt
 * @property String $mobile_country_code
 * @property String $mobile_network_code
 * @property String $mobile_local_area_code
 * @property String $mobile_cel_tower_id
 * @property String $tipo_log
 */
class Logs extends Model
{
    protected $table = 'frota_tracker_logs';

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

    protected $casts = [
        'gps_lat' => 'float',
        'gps_lng' => 'float',
    ];


    public function veiculo()
    {
        return $this->hasOne(Veiculos::class, 'id', 'veiculo_id');
    }

}
