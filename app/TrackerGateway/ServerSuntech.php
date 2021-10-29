<?php


namespace App\TrackerGateway;


use App\TrackerGateway\GT04\Protocol;
use App\TrackerGateway\GT04\Util;
use App\TrackerGateway\Suntech\ProtocolST300;
use Predis\Autoloader;
use Predis\Client;
use React\EventLoop\Factory;
use React\Socket\Server as ReactServer;
use React\Socket\ConnectionInterface;


class ServerSuntech
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

                $msgs = [$data];

                /**
                 * Percorrer cada msg recebida
                 */
                foreach ($msgs as $msg) {

                    try {

                        /**
                         * Fazer parser da msg
                         */
                        $parsed = ProtocolST300::parseString($msg);

                        if (!$parsed) {
                            self::log("{$connection->getRemoteAddress()} -> Mensagem invalida: {$msg}");
                            return;
                        }


                        /**
                         * Se mensagem de login- logar :)
                         */
                        if (isset($parsed['DEV_ID']) && isset($parsed['HDR'])) {

                            self::log("{$connection->getRemoteAddress()} -> {$parsed['DEV_ID']} {$parsed['HDR']}");

                            /**
                             * vincular ids modulo ao socket
                             */
                            $connection->imei = $parsed['DEV_ID'];


                            /* persite log na fila para ser processado */
                            $client->rpush('FILA_GW',
                                serialize([
                                    't' => 'suntech',
                                    'd' => $parsed, //mesagem decodificada
                                    's' => 'ok', //status
                                    'r' => null, //dados puros para debug
                                    'i' => $connection->imei])); //esn do equipamento

                        }

                        /***
                         * Cado de erro gerar log
                         */
                    } catch (\Exception $e) {

                        $erro = "{$connection->getRemoteAddress()} -> ERRO -> {$e->getMessage()}";
                        self::log($erro);

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
