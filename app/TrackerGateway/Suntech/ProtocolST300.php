<?php


namespace App\TrackerGateway\Suntech;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProtocolST300
{

    /** linha sa200 */

    private static $LAYOUT_SA200STT = ['HDR', 'DEV_ID', 'SW_VER', 'DATE', 'TIME', 'CELL', 'LAT', 'LON', 'SPD',
        'CRS', 'SATT', 'FIX', 'DIST', 'PWR_VOLT', 'I/O', 'MODE', 'MSG_NUM', 'H_METER', 'BCK_VOLT', 'MSG_TYPE'];

    private static $LAYOUT_SA200EMG = ['HDR', 'DEV_ID', 'SW_VER', 'DATE', 'TIME', 'CELL', 'LAT', 'LON', 'SPD', 'CRS', 'SATT',
        'FIX', 'DIST', 'PWR_VOLT', 'I/O', 'EMG_ID', 'H_METER', 'BCK_VOLT', 'MSG_TYPE'];

    private static $LAYOUT_SA200EVT = [
        'HDR', 'DEV_ID', 'SW_VER', 'DATE', 'TIME', 'CELL', 'LAT', 'LON', 'SPD', 'CRS', 'SATT', 'FIX', 'DIST', 'PWR_VOLT',
        'I/O', 'EVT_ID', 'H_METER', 'BCK_VOLT', 'MSG_TYPE'
    ];

    private static $LAYOUT_SA200ALT = [
        'HDR', 'DEV_ID', 'SW_VER', 'DATE', 'TIME', 'CELL', 'LAT', 'LON', 'SPD', 'CRS', 'SATT', 'FIX', 'DIST', 'PWR_VOLT',
        'I/O', 'ALERT_ID', 'H_METER', 'BCK_VOLT', 'MSG_TYPE'
    ];

    /**
     * Linha st300
     */


    private static $LAYOUT_ST300STT = [
        'HDR', 'DEV_ID', 'MODEL', 'SW_VER', 'DATE', 'TIME', 'CELL', 'LAT', 'LON', 'SPD', 'CRS', 'SATT', 'FIX', 'DIST', 'PWR_VOLT', 'I/O',
        'MODE', 'MSG_NUM', 'H_METER', 'BCK_VOLT', 'MSG_TYPE', 'RPM', 'DID', 'DID_REG', 'CELL_ID', 'MCC', 'MNC', 'RX_LVL', 'LAC',
        'TM_ADV', 'GPS_ON_OFF'
    ];

    private static $LAYOUT_ST300EMG = [
        'HDR', 'DEV_ID', 'MODEL', 'SW_VER', 'DATE', 'TIME', 'CELL', 'LAT', 'LON', 'SPD', 'CRS', 'SATT', 'FIX', 'DIST', 'PWR_VOLT', 'I/O', 'EMG_ID',
        'H_METER', 'BCK_VOLT', 'MSG_TYPE', 'RPM', 'DID', 'DID_REG', 'CELL_ID', 'MCC', 'MNC', 'RX_LVL', 'LAC', 'TM_AVD', 'GPS_ON_OFF'
    ];

    private static $LAYOUT_ST300EVT = [
        'HDR', 'DEV_ID', 'MODEL', 'SW_VER', 'DATE', 'TIME', 'CELL', 'LAT', 'LON', 'SPD', 'CRS', 'SATT', 'FIX', 'DIST', 'PWR_VOLT', 'I/O', 'EVT_ID', 'H_METER',
        'BCK_VOLT', 'MSG_TYPE', 'RPM', 'DID', 'DID_REG', 'CELL_ID', 'MCC', 'MNC', 'RX_LVL', 'LAC', 'TM_AVD', 'GPS_ON_OFF'
    ];

    private static $LAYOUT_ST300ALT = [
        'HDR', 'DEV_ID', 'MODEL', 'SW_VER', 'DATE', 'TIME', 'CELL', 'LAT',
        'LON', 'SPD', 'CRS', 'SATT', 'FIX', 'DIST', 'PWR_VOLT', 'I/O',
        'ALERT_ID', 'H_METER', 'BCK_VOLT', 'MSG_TYPE', 'RPM', 'DID', 'DID_REG', 'CELL_ID',
        'MCC', 'MNC', 'RX_LVL', 'LAC', 'TM_AVD', 'GPS_ON_OFF'
    ];

    private static $LAYOUT_ST300UEX = [
        'HDR', 'DEV_ID', 'MODEL', 'SW_VER', 'DATE', 'TIME', 'CELL', 'LAT', 'LON', 'SPD', 'CRS', 'SATT', 'FIX', 'DIST',
        'PWR_VOLT', 'I/O', 'LEN', 'DATA'
    ];

    private static $SUPORTED_MSGS = [
        'SA200STT', 'SA200EMG', 'SA200EVT', 'SA200ALT',
        'ST300STT', 'ST300EMG', 'ST300EVT', 'ST300ALT', 'ST300UEX'
    ];

    private static function map($layout, $valores)
    {
        $temp = [];
        foreach ($layout as $posicao => $key) {
            $temp[$key] = @$valores[$posicao];
        }
        return $temp;

    }

    /**
     * Calculate 8 bit checksum for Suntech STN100 protocol version
     * @param $data
     * @return string
     */
    public static function calculateCheckSumSTN100($data)
    {
        $aux = str_split($data);

        $sum = 0;
        foreach ($aux as $char) {
            $sum += ord($char);
        }

        $bin_num = str_pad(substr(decbin($sum), -8), 8, '0');

        return str_pad(strtoupper(base_convert($bin_num, 2, 16)), 2, '0', STR_PAD_LEFT);
    }

    public static function parseString($msg)
    {
        $aux = explode(';', (string)$msg);

        if ($aux && count($aux) > 1) {

            /** Mensagens de status */
            if (in_array($aux[0], self::$SUPORTED_MSGS) && $aux[1] !== 'Res') {

                $layout = "LAYOUT_{$aux[0]}";

                return self::map(self::$$layout, $aux);

            }

            /** ping */
            if ($aux[0] == 'SA200ALV') {
                /* @todo atualizar status equipamentos */
            }


        }

        return false;

    }

}
