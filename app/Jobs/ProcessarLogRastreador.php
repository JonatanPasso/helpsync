<?php

namespace App\Jobs;

use App\Models\Frota\Logs;
use App\Models\Frota\Rastreadores;
use App\TrackerGateway\GtwCache;
use App\TrackerGateway\Suntech\ProtocolST300;
use Carbon\Carbon;

class ProcessarLogRastreador extends Job
{
    private $equipamentos = [];


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    private function getVeiculoId($rastreador_esn)
    {
        foreach ($this->equipamentos as $eqp) {
            if ((int)$eqp->esn == (int)$rastreador_esn) {
                return $eqp->veiculo->id;
            }
        }
        return null;
    }

    /**
     *
     * @param array $dados
     * @
     */
    private function persistirLog(array $dados, $lastLog)
    {

        $log = new Logs();

        $timestampFixed = Carbon::parse($dados['timestamp'])->subHours(3);

        $log->rastreador_esn = (int)$dados['esn'];
        $log->veiculo_id = $this->getVeiculoId($log->rastreador_esn);
        $log->motorista_id = null;
        $log->gps_timestamp = $timestampFixed;
        $log->server_timestamp = date('Y-m-d H:i:s');
        $log->gps_lat = $dados['gps_lat'];
        $log->gps_lng = $dados['gps_lng'];
        $log->gps_speed = @$dados['gps_speed']; //km/h
        $log->gps_dir = $dados['gps_direction'];
        $log->gps_fixo = $dados['gps_fixed'];
        $log->gps_quality = hexdec(substr($dados['gps_quality'], 1, 1));
        $log->gps_online = @$dados['gps_online'];
//        $log->bateria_volt = $dados[''];
        $log->mobile_country_code = @$dados['mobile_country_code'];
        $log->mobile_network_code = @$dados['mobile_network_code'];
        $log->mobile_local_area_code = @$dados['mobile_local_area_code'];
        $log->mobile_cel_tower_id = @$dados['mobile_cel_tower_id'];

        $log->tipo_log = '1';

        if ($lastLog) {

            /**
             *   "packLength" => 10
             * "protocolNumber" => 19
             * "serialNumber" => 104
             * "errorCheck" => 7134
             * "msg" => "STATUS_INFORMATION"
             * "oil_eletricity" => "ON"
             * "device_event" => "Normal"
             * "gps_enabled" => "ON"
             * "device_charge" => "OFF"
             * "acc_level" => "high"
             * "acc_status" => "OFF"
             * "voltage_level" => "Very High"
             * "gsm_signal" => "strong signal"
             * "alarm_2" => "normal"
             * "lang_alarm" => "English"
             */

//            $log->pos_chave = $lastLog['acc_status'] == 'ON' ? 1 : 0;
            $log->pos_chave = $lastLog['acc_level'] == 'high' ? 1 : 0;

        }

        $log->save();

        $key = "last_log_{$log->rastreador_esn}";


        GtwCache::getRedisClient()->set($key, serialize($log));

        GtwCache::add_fila_eventos($log->veiculo_id, $log);

        return $log;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {


        $tamanhoLote = 1000;
        $timeStart = time();

        $this->log("Rontina iniciada");


        while (GtwCache::getRedisClient()->llen('FILA_GW') > 0) {

            $this->log("Lista de rastreadores carregada");

            $this->equipamentos = Rastreadores::query()
                ->with('veiculo')
                ->has('veiculo')
                ->get();

            $this->log("logs pendentes");

            app('db')->beginTransaction();

            $auxLogUnserialized = null;

            try {

                $i = 0;
                do {
                    if (GtwCache::getRedisClient()->llen('FILA_GW')) {

                        $auxLogUnserialized = GtwCache::getRedisClient()->lpop('FILA_GW');

                        $log = (unserialize($auxLogUnserialized));

                        if (@$log['t'] == 'suntech') {
                            $this->tratarLogSuntch($log);
                        } else if ($log['s'] == 'ok') {
                            $this->tratarLog($log);
                        }

//                        app('db')->table('frota_tracker_raw_logs')
//                            ->insert([
//                                'processado_em' => Carbon::now(),
//                                'status' => $log['s'],
//                                'log' => json_encode($log['d']),
//                                'esn' => (int)$log['i']
//                            ]);

                        $i++;
                    } else {
                        break;
                    }
                } while ($i < $tamanhoLote);

                app('db')->commit();

            } catch (\Exception $e) {

                $this->log('Erro ao processar logs:' . $e->getMessage(), 'error');

                if ($auxLogUnserialized) GtwCache::getRedisClient()->lpush('FILA_GW', $auxLogUnserialized);

            }

            $timeStop = time();
            $timeElapsed = $timeStop - $timeStart;

            $this->log("{$i} log(s) processados em {$timeElapsed} s");

        }


        $this->log("Rotina finalizada");


    }

    protected function tratarLog(array $log)
    {

        $esn = (int)$log['i'];

        $key = "last_status_{$esn}";

        $dados = $log['d'];

        if ($dados['msg'] == 'STATUS_INFORMATION') {

            GtwCache::getRedisClient()->set($key, serialize($dados));
        }

        if ($dados['msg'] == 'GPS_LBS_MSG') {

            $lastStatus = GtwCache::getRedisClient()->get($key);
            $lastStatus = $lastStatus ? unserialize($lastStatus) : null;

            $this->persistirLog($dados, $lastStatus);

        }

    }

    protected function tratarLogSuntch($log)
    {
        $dados = $log['d'];


        $log = new Logs();


        $timestampFixed = Carbon::createFromFormat('YmdH:i:s', "{$dados['DATE']}{$dados['TIME']}");

        $log->rastreador_esn = (int)$dados['DEV_ID'];
        $log->veiculo_id = $this->getVeiculoId($log->rastreador_esn);
        $log->motorista_id = null;

        $log->gps_timestamp = $timestampFixed->subHours(3);

        $log->server_timestamp = date('Y-m-d H:i:s');

        $log->gps_lat = $dados['LAT'];
        $log->gps_lng = $dados['LON'];

        $log->gps_speed = $dados['SPD']; //km/h

        $log->gps_dir = $dados['CRS'];

        $log->gps_fixo = $dados['FIX'];

        $log->gps_quality = $dados['SATT'];

        $log->gps_online = $dados['MSG_TYPE'];

        $log->bateria_volt = $dados['PWR_VOLT'];
        //     $log->mobile_country_code = @$dados['MCC'];
        //     $log->mobile_network_code = @$dados['MNC'];
        //      $log->mobile_local_area_code = @$dados['DID'];
//        $log->mobile_cel_tower_id = @$dados['CELL'];

        $log->horimetro = @$dados['H_METER'];
        $log->distance = @$dados['DIST'];

        $log->tipo_log = '2';

        $log->pos_chave = (int)substr($dados['I/O'], 0, 1);

        $log->e1 = (int)substr($dados['I/O'], 1, 1);


        $log->e2 = substr($dados['I/O'], 2, 1);
        $log->e3 = substr($dados['I/O'], 3, 1);

        $log->s1 = substr($dados['I/O'], 4, 1);
        $log->s2 = substr($dados['I/O'], 5, 1);

        if (strpos($dados['HDR'], 'EMG') !== false) {
            $log->tracker_event_id = $dados['EMG_ID'];
        }

        $log->save();


        $key = "last_log_{$log->rastreador_esn}";

        GtwCache::getRedisClient()->set($key, serialize($log));

        GtwCache::add_fila_eventos($log->veiculo_id, $log);


        return $log;


    }


}
