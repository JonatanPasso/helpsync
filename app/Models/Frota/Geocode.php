<?php

namespace App\Models\Frota;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Geocode
 * @package App\Models\Forta
 *
 * @property int $id
 */
class Geocode extends Model
{
    protected $table = 'frota_geocode';

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


    public static function geo_cache($lat, $lng, $distancia = .1/*KM*/)
    {

        $lat = (float)$lat;
        $lng = (float)$lng;

        $sql_magic = "SELECT m.id,m.from,m.formated_address,m.lat,m.lng,m.data,
                p.distance_unit
                         * DEGREES(ACOS(COS(RADIANS(p.latpoint))
                         * COS(RADIANS(m.lat))
                         * COS(RADIANS(p.longpoint) - RADIANS(m.lng))
                         + SIN(RADIANS(p.latpoint))
                         * SIN(RADIANS(m.lat)))) AS distance_in_km
            FROM frota_geocode AS m
            JOIN (
                  SELECT {$lat} AS latpoint,{$lng} AS longpoint,
                         {$distancia} AS radius, 111.045 AS distance_unit
                 ) AS p ON 1=1
            WHERE m.lat
            BETWEEN p.latpoint  - (p.radius / p.distance_unit)
                AND p.latpoint  + (p.radius / p.distance_unit)
                AND m.lng BETWEEN p.longpoint - (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
                AND p.longpoint + (p.radius / (p.distance_unit * COS(RADIANS(p.latpoint))))
            ORDER BY distance_in_km";

        $result = app('db')->select($sql_magic);

        if ($result) {
            $result[0]->data = json_decode($result[0]->data);
            return $result[0];
        }

        return null;

    }
}
