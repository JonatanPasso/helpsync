<?php

namespace App\Models\Frota;

use Illuminate\Database\Eloquent\Model;


/**
 * Class LastLogs
 * @package App\Models\Rastreio
 *
 * @property int $rastreador_esn
 * @property int $log_id
 * @property int $motorista_id
 * @property String $gps_timestamp
 */
class LastLog extends Model
{
    protected $table = 'frota_tracker_last_log';

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


    public function logs()
    {
        return $this->hasOne(Logs::class, 'id', 'log_id');
    }


}
