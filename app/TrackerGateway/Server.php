<?php


namespace App\TrackerGateway;


use App\TrackerGateway\GT04\Protocol;
use App\TrackerGateway\GT04\Util;
use Predis\Autoloader;
use Predis\Client;
use React\EventLoop\Factory;
use React\Socket\Server as ReactServer;
use React\Socket\ConnectionInterface;


class Server
{

    public static function log($msg)
    {
        echo date('Ymd H:i') . '->' . $msg . "\r\n";
    }

    protected $PORT = 9090;

    protected $CONNECTIONS = [];

    protected $COUNT_CON = 0;

    protected $TIMEOUT_CON = 300;

    protected function addConexao($con)
    {
        $this->CONNECTIONS[] = $con;
    }

    protected function delConexao($con)
    {
        foreach ($this->CONNECTIONS as $i => $c) {
            if ($c->conexao_id == $con->conexao_id) {
                $this->CONNECTIONS[$i] = null;
                unset($this->CONNECTIONS[$i]);
                break;
            }
        }
    }


    public function __construct()
    {

        Autoloader::register();

        $client = new Client();

        $loop = Factory::create();

        $socket = new ReactServer('0.0.0.0:' . $this->PORT, $loop);

        self::log("AGUARDANDO CONEXAO NA PORTA {$this->PORT}");

        $socket->on('connection', function (ConnectionInterface $connection) use ($client, $loop) {

            $this->COUNT_CON++;

            self::log("{$connection->getRemoteAddress()} CONEXAO ESTABELECIDA");

            $connection->imei = null;
            $connection->conexao_id = $this->COUNT_CON;

            /**
             * @workaround setar timeout para conexoes inativas ( api nao tem essa funcao nativamente )
             */
            $timeoutFunc = function () use ($connection) {
                $connection->close();
            };

            $timer = $loop->addTimer($this->TIMEOUT_CON, $timeoutFunc);


            $connection->on('data', function ($data) use ($connection, $client, &$timer, $timeoutFunc, $loop) {

                /**
                 * @workaround cancela timeout atual caso receba dados, a agenda proximo timeout para fehcar conexao caso nao
                 * haja mais envios
                 */
                $loop->cancelTimer($timer);
                $timer = $loop->addTimer($this->TIMEOUT_CON, $timeoutFunc);


                /**
                 * Extrair dos bytes para array buffer
                 */
                $temp = array_values(unpack('C*', $data));

                /**
                 * Seperar as msg agrudas recebidas
                 */
                $byteMsgs = Util::desagrupar_msgs($temp);

                /**
                 * Percorrer cada msg recebida
                 */
                foreach ($byteMsgs as $byteMsg) {

                    try {

                        /**
                         * Fazer parser da msg
                         */
                        $msg = Protocol::parse($byteMsg);

                        /**
                         * Se mensagem de login- logar :)
                         */
                        if ($msg['protocolNumber'] == Protocol::LOGIN_MSG) {

                            self::log("{$connection->getRemoteAddress()} -> {$msg['terminalId']} MSG LOGIN SEQ {$msg['serialNumber']}");

                            /**
                             * Enviar ack
                             */
                            $ack = Protocol::ackLogin($byteMsg);
                            $connection->write(pack('C*', ...$ack));

                            /**
                             * vincular ids modulo ao socket
                             */
                            $connection->imei = $msg['terminalId'];


                            /**
                             * Se mensgem de localizaão e status do gsm
                             */
                        } else if ($msg['protocolNumber'] == Protocol::GPS_LBS_MSG) {

                            //   self::log("{$connection->getRemoteAddress()} -> {$connection->imei} msg GPS LBS SEQ {$msg['serialNumber']}");

                            // add imei ao msg decodificada
                            $msg['esn'] = $connection->imei;

                            // se mensagem de alarm
                        } else if ($msg['protocolNumber'] == Protocol::ALARM_DATA) {

                            //     self::log("{$connection->getRemoteAddress()} -> {$connection->imei} msg ALARM SEQ {$msg['serialNumber']}");

                            // enviar ack
                            $ackALARM = Protocol::ackLogin($byteMsg);
                            $connection->write(pack('C*', ...$ackALARM));
                            //    self::log("{$connection->getRemoteAddress()} -> {$connection->imei} msg SEND ACK ALARM");


                            // se mensagem de status
                        } else if ($msg['protocolNumber'] == Protocol::STATUS_INFORMATION) {

                            //     self::log("{$connection->getRemoteAddress()} -> {$connection->imei} msg HEARTBEAT SEQ {$msg['serialNumber']}");

                            //enviar ack
                            $ackHeartBeat = Protocol::ackHeartBeat($byteMsg);
                            $connection->write(pack('C*', ...$ackHeartBeat));

                         //   self::log("{$connection->getRemoteAddress()} -> {$connection->imei} msg SEND ACK HEARTBEAT");

                            //outros tipos msg - ignorar
                        } else {

                            self::log("{$connection->getRemoteAddress()} -> {$connection->imei} MSG NAO IMPLEMENTADA: " . dechex($msg['protocolNumber']));

                        }

                        /* persite log na fila para ser processado */
                        $client->rpush('FILA_GW',
                            serialize([
                                'd' => $msg, //mesagem decodificada
                                's' => 'ok', //status
                                'r' => Util::bytesToHex($byteMsg), //dados puros para debug
                                'i' => $connection->imei])); //esn do equipamento


                        /***
                         * Cado de erro gerar log
                         */
                    } catch (\Exception $e) {

                        $erro = "{$connection->getRemoteAddress()} -> ERRO -> {$e->getMessage()}";
                        self::log($erro);

                        /* persite log na fila para ser processado */
                        $client->rpush('FILA_GW', serialize([
                            'd' => $erro,
                            's' => 'erro',
                            'r' => $byteMsg,
                            'i' => $connection->imei]));
                    }

                }

            });

            $connection->on('close', function () use ($connection) {

                self::log("{$connection->getRemoteAddress()} -> {$connection->imei} CONEXAO FECHADA");

                $this->delConexao($connection);
            });

            $this->addConexao($connection);

        });

        $loop->addPeriodicTimer(10, function () use ($client) {

            $qtdConexcoes = count($this->CONNECTIONS);

            $filaMsg = $client->llen('FILA_GW');

            self::log("Status Servidor: Conexoes:{$qtdConexcoes}, Fila Msg:{$filaMsg}");

            $client->set('gw_status', serialize([
                'connections' => $qtdConexcoes,
                'queue' => $filaMsg,
            ]));


        });

        $loop->run();


    }
}
