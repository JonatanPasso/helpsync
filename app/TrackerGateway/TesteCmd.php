<?php


namespace App\TrackerGateway;


use App\TrackerGateway\GT04\Protocol;
use App\TrackerGateway\GT04\Util;
use Illuminate\Console\Command;

class TesteCmd extends Command
{


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teste-gateway';


    public function handle()
    {
        $this->TestarHeatBeat();
    }


    public function TestarHeatBeat()
    {

        $a = str_split('78781f12140116010c31c501cb91ff054ac0bf00180002d40600a2000107284a9f2a0d0a78781f12140116011010c501cb91ff054ac0bf00180002d40600a2000107284b845d0d0a78781f12140116011235c501cb91ff054ac0bf00180002d40600a2000107284c48840d0a',2);

        foreach ($a as $k =>$v) $a[$k] = hexdec($v);

        dump(Util::desagrupar_msgs($a));

        exit;
//        Util::desagrupar_msgs()




        $loginFrame = '78 78 0D 01 03 53 41 35 32 15 03 62 00 02 2D 06 0D 0A';
        $lbsGpsFrame = '78 78 1F 12 0B 08 1D 11 2E 10 CF 02 7A C7 EB 0C 46 58 49 00 14 8F 01 CC 00 28 7D 00 1F B8 00 03 80 81 0D 0A';
        $statusFrame = '78 78 0A 13 44 01 04 00 01 00 05 08 45 0D 0A';
                  //   0  1  2  3  4  5  6  7  8  9  10 11 12 13 14 15 16 17 18 19 20 21 22 23 24 25 26 27 28 29 30 31 32 33 34 35 36 37 38 39 40 41
        $alarmFrame = '78 78 25 16 0B 0B 0F 0E 24 1D CF 02 7A C8 87 0C 46 57 E6 00 14 02 09 01 CC 00 28 7D 00 1F 72 65 06 04 01 01 00 36 56 A4 0D 0A';

                      //   0  1  2  3  4  5  6  7  8  9  10 11 12 13 14
        $heartBeatFrame = '78 78 0A 13 44 01 04 00 01 00 05 08 45 0D 0A';
        $heartBeatFrameAck = '787805130005AFD50D0A';

        $pack = Util::stringToBytes(str_replace(' ', '', $heartBeatFrame));

        $result = Protocol::parse($pack);

        $ackHeartBeat = Protocol::ackHeartBeat($pack);
        $ackTOSend = strtoupper(Util::bytesToHex($ackHeartBeat));

        var_dump($ackTOSend == $heartBeatFrameAck);



    }

}
