<?php


namespace App\TrackerGateway\GT04;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Protocol
{
    public const LOGIN_MSG = 0x01;
    public const GPS_LBS_MSG = 0x12;
    public const START_BIT1 = 0x78;
    public const START_BIT2 = 0x78;
    public const END_BIT1 = 0x0D;
    public const END_BIT2 = 0x0A;

    public const STATUS_INFORMATION = 0x13;
    public const STRING_INFORMATION = 0x15;
    public const ALARM_DATA = 0x16;
    public const GPS_QUERY_ADDRES = 0x1A;
    public const CMD_SEND_BY_SERVER = 0x80;


    public static function validaMsg($data)
    {
        $size = count($data);

        if ($size > 8) {
            if ($data[0] == self::START_BIT1 &&
                $data[1] == self::START_BIT2 &&
                $data[$size - 2] == self::END_BIT1 &&
                $data[$size - 1] == self::END_BIT2) {

                $crc = Util::stringToBytes(self::crc($data));
                $crcFromMsg = [$data[$size - 4], $data[$size - 3]];

                if ($crc[0] == $crcFromMsg[0] && $crc[1] == $crcFromMsg[1]) {

                    return true;
                } else {
                    dump($crc, $crcFromMsg, Util::bytesToHex($data));
                }

            }
        }


        return false;

    }

    public static function crc(array $data)
    {

        $aux = array_slice($data, 2, count($data) - 6);
        return str_pad(dechex(Util::crc8($aux)), '4', '0', STR_PAD_LEFT);

    }

    public static function ackLogin(array $msg)
    {

        $size = count($msg);

        $msg = [
            //START BITS
            self::START_BIT1, self::START_BIT2,
            //LENGTH defult for ack login
            0X05,
            //PROTOCOL NUMBER
            self::LOGIN_MSG,
            //serial msg
            $msg[$size - 6], $msg[$size - 5],
            //error check - full next time
            0, 0,
            //stop bits
            self::END_BIT1,
            self::END_BIT2,
        ];

        $crc = Util::stringToBytes(self::crc($msg));

        $msg[6] = $crc[0];
        $msg[7] = $crc[1];

        return $msg;

    }


    public static function ackAlarm(array $msg)
    {

        $size = count($msg);

        $msg = [
            //START BITS
            self::START_BIT1, self::START_BIT2,
            //LENGTH defult for ack login
            0x05,
            //PROTOCOL NUMBER
            self::ALARM_DATA,
            //serial msg
            $msg[$size - 6], $msg[$size - 5],
            //error check - full next time
            0, 0,
            //stop bits
            self::END_BIT1,
            self::END_BIT2,
        ];

        $crc = Util::stringToBytes(self::crc($msg));

        $msg[6] = $crc[0];
        $msg[7] = $crc[1];

        return $msg;

    }

    public static function ackHeartBeat(array $msg)
    {

        $size = count($msg);

        $msg = [
            //START BITS
            self::START_BIT1, self::START_BIT2,
            //LENGTH defult for ack login
            0x05,
            //PROTOCOL NUMBER
            self::STATUS_INFORMATION,
            //serial msg
            $msg[$size - 6], $msg[$size - 5],
            //error check - full next time
            0, 0,
            //stop bits
            self::END_BIT1,
            self::END_BIT2,
        ];

        $crc = Util::stringToBytes(self::crc($msg));

        $msg[6] = $crc[0];
        $msg[7] = $crc[1];

        return $msg;

    }

    public static function parse(array $msg)
    {

        if (!self::validaMsg($msg)) {

            throw new \Exception('Mensagen invalida!');
        }

        $size = count($msg);

        $pack = [];

        $pack['packLength'] = ($msg[2]);
        $pack['protocolNumber'] = ($msg[3]);

        $pack['serialNumber'] = Util::usingedBytesToInt([$msg[$size - 6], $msg[$size - 5]]);
        $pack['errorCheck'] = Util::usingedBytesToInt([$msg[$size - 4], $msg[$size - 3]]);


        //Login Message
        if ($pack['protocolNumber'] == self::LOGIN_MSG) {
//            echo "Login message\r\n";

            $pack['msg'] = 'LOGIN_MSG';

            $pack['terminalId'] = Util::bytesToHex([$msg[4], $msg[5], $msg[6], $msg[7], $msg[8], $msg[9], $msg[10], $msg[11]]);

        }


        if ($pack['protocolNumber'] == self::GPS_LBS_MSG) {

            $pack['msg'] = 'GPS_LBS_MSG';

            $pack['timestamp'] = Carbon::create($msg[4] + 2000, $msg[5], $msg[6], $msg[7], $msg[8], $msg[9])->format('Y-m-d H:i:s');
            $pack['gps_quality'] = dechex($msg[10]);

            $latAux = hexdec(Util::bytesToHex([$msg[11], $msg[12], $msg[13], $msg[14]]));
            $lat = (90 * $latAux) / 162000000.0;

            $lngAux = hexdec(Util::bytesToHex([$msg[15], $msg[16], $msg[17], $msg[18]]));
            $lng = (180 * $lngAux) / 324000000.0;

            $speed = $msg[19]; //km/h

            $courseStatus = str_pad(decbin($msg[20]), 8, '0', STR_PAD_LEFT);
            $courseStatus .= str_pad(decbin($msg[21]), 8, '0', STR_PAD_LEFT);

            $sinalLongitude = $courseStatus[4] == '1' ? '-' : '+';
            $sinalLatitude = $courseStatus[5] == '0' ? '-' : '+';

            $lat = "$sinalLatitude{$lat}";
            $lng = "$sinalLongitude{$lng}";

            $online = $courseStatus[2];
            $gpsFixed = $courseStatus[3];

            $direcao = bindec(substr($courseStatus, 6));//graus

            //MOBILE COUNTRY CODE
            $mobile_county_code = Util::usingedBytesToInt([$msg[22], $msg[23]]);

            //MOBILE NETWORK CODE
            $mobile_network_code = $msg[24];

            //LOCAL AREA CODE
            $local_area_code = Util::usingedBytesToInt([$msg[25], $msg[26]]);

            //CELL ID
            $cel_tower_id = hexdec(Util::bytesToHex([$msg[27], $msg[28], $msg[28]]));

            $pack['gps_lat'] = $lat;
            $pack['gps_lng'] = $lng;
            $pack['gps_fixed'] = $gpsFixed;
            $pack['gps_online'] = $online;
            $pack['gps_direction'] = $direcao;
            $pack['mobile_country_code'] = $mobile_county_code;
            $pack['mobile_network_code'] = $mobile_network_code;
            $pack['mobile_local_area_code'] = $local_area_code;
            $pack['mobile_cel_tower_id'] = $cel_tower_id;
            $pack['gps_speed'] = $speed;

        }


        if ($pack['protocolNumber'] == self::ALARM_DATA) {


            $pack['msg'] = 'ALARM_DATA';

            $pack['timestamp'] = Carbon::create($msg[4] + 2000, $msg[5], $msg[6], $msg[7], $msg[8], $msg[9])->format('Y-m-d H:i:s');
            $pack['gps_quality'] = dechex($msg[10]);

            $latAux = hexdec(Util::bytesToHex([$msg[11], $msg[12], $msg[13], $msg[14]]));
            $lat = (90 * $latAux) / 162000000.0;

            $lngAux = hexdec(Util::bytesToHex([$msg[15], $msg[16], $msg[17], $msg[18]]));
            $lng = (180 * $lngAux) / 324000000.0;

            $speed = $msg[19]; //km/h

            //COURSE STATUS
            $courseStatus = str_pad(decbin($msg[20]), 8, '0', STR_PAD_LEFT);
            $courseStatus .= str_pad(decbin($msg[21]), 8, '0', STR_PAD_LEFT);

            $sinalLongitude = $courseStatus[4] == '1' ? '-' : '+';
            $sinalLatitude = $courseStatus[5] == '0' ? '-' : '+';

            $lat = "$sinalLatitude{$lat}";
            $lng = "$sinalLongitude{$lng}";

            $online = $courseStatus[2];
            $gpsFixed = $courseStatus[3];

            $direcao = bindec(substr($courseStatus, 6));//graus

            //MCC
            $mobile_county_code = Util::usingedBytesToInt([$msg[23], $msg[24]]);

            //MNC
            $mobile_network_code = $msg[25];

            //LAc - 2 BYTES
            $local_area_code = Util::usingedBytesToInt([$msg[26], $msg[27]]);

            //Cell ID - 3bytes
            $cel_tower_id = hexdec(Util::bytesToHex([$msg[28], $msg[29], $msg[30]]));

            ###    STATUS INFORMATION                    ##################
            ###### Terminal Information Content - 1 byte ##################

            $terminalInformation = str_pad(decbin($msg[31]), 8, '0', STR_PAD_LEFT);
            ########## oil and electricity
            $ignicao = $terminalInformation[0] == 0 ? 'ON' : 'OFF';
            ########## GPS tracking
            $gpsAtivo = $terminalInformation[1] == 1 ? 'ON' : 'OFF';

            ########## ALARMS
            $aux = substr($terminalInformation, 2, 3);
            $alarm = null;
            if ($aux == '100') $alarm = 'SOS';
            if ($aux == '011') $alarm = 'Low Battery Alarm';
            if ($aux == '010') $alarm = 'Power Cut Alarm';
            if ($aux == '001') $alarm = 'Shock Alarm';
            if ($aux == '000') $alarm = 'Normal';

            ########## chargin on
            $charge = $terminalInformation[5] == 1 ? 'ON' : 'OFF';

            ########## ACC LEVEL
            $acc_level = $terminalInformation[6] == 1 ? 'high' : 'low';

            #acc active
            $acc_status = $terminalInformation[7] == 1 ? 'ON' : 'OFF';

            ###### VOLTAGE LEVEL - 1 byte #################################
            $voltage_level = $msg[32];

            ######  GSM Signal Strength - 1 byte ##########################
            $gsm_signal = $msg[33];

            ######  Alarm/Language - 1 byte ##########################

            $array = [
                00 => 'normal',
                01 => 'SOS',
                02 => 'Power Cut Alarm',
                03 => 'Shock Alarm',
                04 => 'Fence In Alarm',
                05 => ' Fence Out Alarm'];

            $alarm2 = $array[$msg[34]];

            $lang_alarm = $msg[35];


            $pack['gps_lat'] = $lat;
            $pack['gps_lng'] = $lng;
            $pack['gps_fixed'] = $gpsFixed;
            $pack['gps_online'] = $online;
            $pack['gps_direction'] = $direcao;
            $pack['mobile_country_code'] = $mobile_county_code;
            $pack['mobile_network_code'] = $mobile_network_code;
            $pack['mobile_local_area_code'] = $local_area_code;
            $pack['mobile_cel_tower_id'] = $cel_tower_id;
            $pack['gps_speed'] = $speed;

            $pack['oil_eletricity'] = $ignicao;
            $pack['device_event'] = $alarm;
            $pack['gps_enabled'] = $gpsAtivo;
            $pack['device_charge'] = $charge;
            $pack['acc_status'] = $acc_level;
            $pack['acc_status'] = $acc_status;

            $pack['voltage_level'] = $voltage_level;
            $pack['gsm_signal'] = $gsm_signal;
            $pack['alarm_2'] = $alarm2;
            $pack['lang_alarm'] = $lang_alarm;


        }

        if ($pack['protocolNumber'] == self::STATUS_INFORMATION) {


            $pack['msg'] = 'STATUS_INFORMATION';

            ###    STATUS INFORMATION                    ##################
            ###### Terminal Information Content - 1 byte ##################

            $terminalInformation = str_pad(decbin($msg[4]), 8, '0', STR_PAD_LEFT);
            ########## oil and electricity
            $ignicao = $terminalInformation[0] == 0 ? 'ON' : 'OFF';
            ########## GPS tracking
            $gpsAtivo = $terminalInformation[1] == 1 ? 'ON' : 'OFF';

            ########## ALARMS
            $aux = substr($terminalInformation, 2, 3);
            $alarm = null;
            if ($aux == '100') $alarm = 'SOS';
            if ($aux == '011') $alarm = 'Low Battery Alarm';
            if ($aux == '010') $alarm = 'Power Cut Alarm';
            if ($aux == '001') $alarm = 'Shock Alarm';
            if ($aux == '000') $alarm = 'Normal';

            ########## chargin on
            $charge = $terminalInformation[5] == 1 ? 'ON' : 'OFF';

            ########## ACC LEVEL
            $acc_level = $terminalInformation[6] == 1 ? 'high' : 'low';

            #acc active
            $acc_status = $terminalInformation[7] == 1 ? 'ON' : 'OFF';

            ###### VOLTAGE LEVEL - 1 byte #################################

            $voltage_level_onfig = [
                0 => 'No Power',
                1 => 'Extremely Low Battery',
                2 => 'Very Low Battery',
                3 => 'Low Battery',
                4 => 'Medium',
                5 => 'High',
                6 => 'Very High'];

            $voltage_level = $voltage_level_onfig[$msg[5]];

            ######  GSM Signal Strength - 1 byte ##########################
            $gsm_sigal_levels = [
                0 => 'no signal',
                1 => 'extremely weak signal',
                2 => 'very weak signal',
                3 => 'good signal',
                4 => 'strong signal'
            ];

            $gsm_signal = $gsm_sigal_levels[$msg[6]];

            ######  Alarm/Language - 1 byte ##########################

            $array = [
                00 => 'normal',
                01 => 'SOS',
                02 => 'Power Cut Alarm',
                03 => 'Shock Alarm',
                04 => 'Fence In Alarm',
                05 => 'Fence Out Alarm'];

            $alarm2 = $array[$msg[7]];

            $lang = [1 => 'Chinese', 2 => 'English'];

            $lang_alarm = $lang[$msg[8]];

            $pack['oil_eletricity'] = $ignicao;
            $pack['device_event'] = $alarm;
            $pack['gps_enabled'] = $gpsAtivo;
            $pack['device_charge'] = $charge;
            $pack['acc_level'] = $acc_level;
            $pack['acc_status'] = $acc_status;

            $pack['voltage_level'] = $voltage_level;
            $pack['gsm_signal'] = $gsm_signal;
            $pack['alarm_2'] = $alarm2;
            $pack['lang_alarm'] = $lang_alarm;

        }

        return $pack;


    }
}
