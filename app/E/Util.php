<?php

namespace App\E;

use Carbon\Carbon;
use Illuminate\Support\Str;

class Util
{


    public static function parte_int($float)
    {

        $aux = (string)$float;
        $aux2 = explode('.', $aux);

        return $aux2[0];
    }


    public static function parte_float($float, $casas = 2)
    {

        $aux = (string)$float;
        $aux2 = explode('.', $aux);

        $aux3 = array_fill(0, $casas, '0');
        if (count($aux2) == 2) {
            $aux3 = substr($aux2[1], 0, $casas);
        }

        return $aux3;
    }


    public static function whatsAppLinkDesk($phone, $texto = '')
    {

        $phone = preg_replace('/\D/', '', $phone);

        $texto = urlencode($texto);

//        return "https://api.whatsapp.com/send?phone={$phone}&text={$texto}";
        return "https://web.whatsapp.com/send?phone={$phone}&text={$texto}";
    }

    public static function whatsAppLinkMobl($phone, $texto = '')
    {

        $phone = preg_replace('/\D/', '', $phone);

        $texto = urlencode($texto);

        return "https://api.whatsapp.com/send?phone={$phone}&text={$texto}";
//        return "https://web.whatsapp.com/";
    }

    public static function num_br($numero, $casas = 2)
    {
        return number_format($numero, $casas, ',', '.');
    }


    public static function mask($val, $mask)
    {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k]))
                    $maskared .= $val[$k++];
            } else {
                if (isset($mask[$i]))
                    $maskared .= $mask[$i];
            }
        }
        return $maskared;
    }

    public static function fData($dataEntrada, $formato = 'd/m/Y H:i:s', $formatoEntrada = null)
    {

        try {
            if ($formatoEntrada) {
                $parsedeData = Carbon::createFromFormat($dataEntrada, $formatoEntrada);
            } else {
                $parsedeData = Carbon::parse($dataEntrada);
            }


            return $parsedeData->format($formato);
        } catch (\Exception $e) {
            return $dataEntrada;
        }
    }

    public static function sanitize($string)
    {
        return preg_replace('/[^\da-z ]/i', '', $string);
    }


// Function to get the client IP address
    public static function get_client_ip()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if (isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if (isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

}
