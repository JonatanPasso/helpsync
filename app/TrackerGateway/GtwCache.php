<?php

namespace App\TrackerGateway;

class GtwCache
{

    private static $redisClient = null;


    public static function getRedisClient()
    {
        if (self::$redisClient) {
            return self::$redisClient;
        }

        return self::$redisClient = new \Predis\Client();
    }

    public static function getLastLog($esn)
    {

        $redisClient = GtwCache::getRedisClient();

        $lastLog = $redisClient->get("last_log_{$esn}");

        return $lastLog ? unserialize($lastLog) : null;


    }

    public static function add_fila_eventos($veiculoId, $log)
    {

        $redisClient = GtwCache::getRedisClient();

        $redisClient->lpush("FILA_EVENTOS_{$veiculoId}",
            gzcompress(serialize($log)));

        $redisClient->ltrim("FILA_EVENTOS_{$veiculoId}", 0, 100);

    }

    public static function get_fila_eventos($veiculoId)
    {

        $redisClient = GtwCache::getRedisClient();

        $aux = $redisClient
            ->lrange("FILA_EVENTOS_{$veiculoId}", 0, -1);

        $unserialized = [];

        if($aux){
            foreach ($aux as $l)$unserialized[] = unserialize(gzuncompress($l));
        }

        return $unserialized;
    }


}
