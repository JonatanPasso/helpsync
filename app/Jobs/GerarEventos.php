<?php

namespace App\Jobs;

use App\Models\Frota\Eventos;
use App\Models\Frota\HistoricoEventos;
use App\Models\Frota\Veiculos;
use App\Models\Geral\Clientes;
use App\Models\Geral\Notificacoes;
use App\Models\Geral\Usuarios;
use App\TrackerGateway\GtwCache;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class GerarEventos extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->log("Rontina iniciada");

        $timestart = time();

        $veiculosComEvento = Veiculos::query()
            ->with('eventos')
            ->has('eventos')
            ->get();

        app('db')->beginTransaction();


        foreach ($veiculosComEvento as $veiculo) {

            $this->log("veiculo: {$veiculo->placa}");

            foreach ($veiculo->eventos as $evento) {

                $rawLog = GtwCache::get_fila_eventos($veiculo->id);

                if (!$rawLog) continue;

                $logs = collect($rawLog);

                if ($evento->evento == Eventos::VELOCIDADE) {
                    $this->gerarEventoVelocidade($veiculo, $evento, $logs);
                }

                if ($evento->evento == Eventos::IGNICAO) {
                    $this->gerarEventoIgnicao($veiculo, $evento, $logs);
                }

                if ($evento->evento == Eventos::LIGADO_E_PARADO) {
                    $this->gerarEventoLigadoParado($veiculo, $evento, $logs);
                }
            }
        }

        app('db')->commit();

        $timefinish = time();

        $timediff = $timefinish - $timestart;

        $this->log("Rontina finalizada em {$timediff}s");

    }


    function gerarEventoVelocidade(Veiculos $veiculo, Eventos $evento, $logs)
    {


        $filtered = $logs
            ->where('gps_speed', '>=', (int)$evento->vel_min)
            ->where('gps_speed', '<=', (int)$evento->vel_max);

        if ($filtered->count()) {

            if ($this->timeoutUltimoEvento($veiculo, $evento, 10)) {
                $this->log("Veiculo {$veiculo->placa} -> Aguardar 10 minutos");
                return;
            }

            $this->gravarEvento($veiculo, $evento, $filtered->first());

        }

    }

    /**
     * @param Veiculos $veiculo Veículo
     * @param Eventos $evento Evento
     * @param int $timeout Tempo em minutos
     * @return bool true se ultimo historico evento for menor que timeout
     */
    function timeoutUltimoEvento(Veiculos $veiculo, Eventos $evento, $timeout = 10)
    {

        $ultimoEvento = HistoricoEventos::query()
            ->where('veiculo_id', $veiculo->id)
            ->where('evento_id', $evento->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($ultimoEvento) {
            $timeLastEvent = time() - strtotime($ultimoEvento->data_hora_processado);

            if ($timeLastEvent < ($timeout * 60)) { // minutos
                return true;
            }

        }
        return false;
    }

    function gerarEventoIgnicao(Veiculos $veiculo, Eventos $evento, \Illuminate\Support\Collection $logs)
    {

        /** ordenar logs por timestamp */

        $sorted = $logs->sortBy(function ($item) {
            return $item->gps_timestamp->timestamp;
        })->values();

        foreach ($sorted as $index => $pAtual) {

            $pProxima = $sorted->get($index + 1);

            if (!$pProxima) break;

            //VEICULO LIGADO
            if ($pAtual->pos_chave == 0 && $pProxima->pos_chave == 1) {
                $this->gravarEvento($veiculo, $evento, $pProxima);
            }

            //VEICULO DESLIGADO
            if ($pAtual->pos_chave == 1 && $pProxima->pos_chave == 0) {
                $this->gravarEvento($veiculo, $evento, $pProxima);
            }

        }

    }

    function gerarEventoLigadoParado(Veiculos $veiculo, Eventos $evento, \Illuminate\Support\Collection $logs)
    {
        /** ordenar logs por timestamp */

        $sorted = $logs->sortBy(function ($item) {
            return $item->gps_timestamp->timestamp;
        })->values();

        $somaTempo = 0;


        foreach ($sorted as $index => $pAtual) {

            $pProxima = $sorted->get($index + 1);

            if (!$pProxima) break;

            //VEICULO LIGADO
            if ($pAtual->pos_chave == 1 &&
                $pProxima->pos_chave == 1 &&
                $pAtual->gps_speed == 0 &&
                $pProxima->gps_speed == 0) {

                $somaTempo += $pProxima->gps_timestamp->diffInSeconds($pAtual->gps_timestamp);

            } else {
                $somaTempo = 0;
            }

            //ligado parado > x minutos
            if ($somaTempo && $somaTempo > ($evento->tempo * 60)) {

                if ($this->timeoutUltimoEvento($veiculo, $evento, 10)) {
                    $this->log("Veiculo {$veiculo->placa} -> Aguardar 10 minutos");
                    return;
                }

                $this->gravarEvento($veiculo, $evento, $pProxima);
            }

        }

    }

    function gravarEvento(Veiculos $veiculo, Eventos $evento, $posicao)
    {

        $jaRegistrado = HistoricoEventos::query()
            ->where('tracker_log_id', $posicao->id)
            ->where('veiculo_id', $veiculo->id)
            ->where('evento_id', $evento->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($jaRegistrado) {

            $this->log("Veiculo {$veiculo->placa} -> Log ja processado. Ignorando");

        } else {

            $historicoEvento = new  HistoricoEventos();
            $historicoEvento->tracker_log_id = $posicao->id;
            $historicoEvento->data_hora_processado = Carbon::now();
            $historicoEvento->veiculo_id = $veiculo->id;
            $historicoEvento->evento_id = $evento->id;

            $historicoEvento->save();

            $this->log("Veiculo {$veiculo->placa} -> Evento {$evento->evento} registrado");

            $this->gerarNotificacoes($historicoEvento);
        }


    }

    public function gerarNotificacoes(HistoricoEventos $evento)
    {

        if ($evento->veiculo && $evento->veiculo->clientes) {

            /**
             * @var Collection $clientes ;
             */
            $clientes = $evento->veiculo->clientes;

            $usuarios = $clientes->reduce(function (\Illuminate\Support\Collection $stack, Clientes $cliente) {
                return $stack->concat($cliente->usuarios()->get());
            }, collect());

            $usuarios->each(function (Usuarios $usuario) use ($evento) {

                $noficicacao = new Notificacoes();
                $noficicacao->usuario_id = $usuario->id;
                $noficicacao->titulo = $evento->evento->evento;
                $noficicacao->msg = $this->gerarMsgNotificacao($evento);
                $noficicacao->gerada_em = Carbon::now();

                $noficicacao->save();
            });

        }

    }

    private function gerarMsgNotificacao(HistoricoEventos $evento)
    {

        $logTracker = $evento->trackerLog;

        if (!$logTracker) {
            return;
        }

        $dataHumama = Carbon::parse($logTracker->gps_timestamp)->format('d/m/Y');
        $horaHumama = Carbon::parse($logTracker->gps_timestamp)->format('H:i');
        $placaVeiculo = $evento->veiculo->placa;


        if ($evento->evento->evento == Eventos::VELOCIDADE) {
            return "Veículo {$placaVeiculo} FORA DOS LIMITE VELOCIDADE ({$logTracker->gps_speed} km/h) EM {$dataHumama} às {$horaHumama}";
        }

        if ($evento->evento->evento == Eventos::IGNICAO) {

            if ($evento->trackerLog->pos_chave == 0) {
                return "Veículo {$placaVeiculo} DESLIGADO EM {$dataHumama} às {$horaHumama}";
            }

            if ($evento->trackerLog->pos_chave == 1) {
                return "Veículo {$placaVeiculo} LIGADO EM {$dataHumama} às {$horaHumama}";
            }

        }

        if ($evento->evento->evento == Eventos::LIGADO_E_PARADO) {
            return "Veículo {$placaVeiculo} LIGADO E PARADO POR {$evento->evento->tempo} MINUTO(S) às {$dataHumama} às {$horaHumama}";
        }

//        if ($evento->evento == TrackerEventos::VEICULO_DESLIGADO) {
//            return "Veículo {$placaVeiculo} DESLIGADO em {$dataHumama} às {$horaHumama} h";
//        }

    }


}
