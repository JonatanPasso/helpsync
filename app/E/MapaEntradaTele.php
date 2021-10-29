<?php

namespace App\E;

use Illuminate\Support\Str;

class MapaEntradaTele
{


    public static function entrada($nomeEntrada)
    {


        $mapa = [];
        //entradas 1 dumpar gelo = 1 -> ativo

        $mapa['tele_ent1'] = 'DAMPER DEGELO';
        //entradas 2 alarme baixo nivel agua
        $mapa['tele_ent2'] = 'NIVEL ARREFECIMENTO';
        //entradas 3 alerma baixo nivel oleo
        $mapa['tele_ent3'] = 'NIVEL DE OLEO MOTOR';
        //entradas 4 abertura tanque combustivel 1 -> aberta, 0 -> fechada
        $mapa['tele_ent4'] = 'ABERTURA TANQUE COMBUSTIVEL';
        //entradas 5 alta rpm
        $mapa['tele_ent5'] = 'ALTA RPM';
        //entradas 6 hpco pressostato de alta - presssao 1|0  se 0 = alerta se ras_entrada1 == 1
        $mapa['tele_ent6'] = 'PRESSOSTADO DE ALTA';
        //entradas 7 solenoid piloto de gelo 1|0
        $mapa['tele_ent7'] = 'SOLENOIDE DEGELO';

        return @$mapa[$nomeEntrada];
    }

    public static function logicaEntrada($entrada, $valor)
    {

        $aux = '';

        if (in_array($entrada, ['tele_ent1'])) {

            $aux = $valor == '0' ? 'ABERTO' : 'FECHADO';

        } else if (in_array($entrada, ['tele_ent2', 'tele_ent3'])) {

            $aux = $valor == '0' ? 'OK' : 'FALHA';

        } else if ($entrada == 'tele_ent6') {

            $aux = $valor == '0' ? 'FALHA' : 'OK';

        } else if ($entrada == 'tele_ent7') {

            $aux = $valor == '0' ? 'DESLIGADA' : 'LIGADA';

        } else if ($entrada == 'tele_ent4') {

            $aux = $valor == '0' ? 'FECHADA' : 'ABERTA';

        } else {

            return $valor == '0' ? 'NAO' : 'SIM';

        }

        return $aux;


    }

    public static function iconesOperacao($modeloEquipamento, $telemetria, $ignicaoOn, $motorON)
    {



        if ($modeloEquipamento == 'TERMOKING') {
            return self::iconesOperacaoTermoKing($telemetria, $ignicaoOn, $motorON);
        }

        if ($modeloEquipamento == 'CONTAINER') {
            return self::iconesOperacaoContainer($telemetria, $ignicaoOn, $motorON);
        }


    }


    public static function iconesOperacaoTermoKing($telemetria, $ignicaoOn, $motorON)
    {
        $base = '/img/rastreamento/icones/OPERACAO/PADRAO';

        /**
         * EQUIPAMENTO LIGADO E MOTOR LIGADO
         */
        if ($ignicaoOn == 1 && $motorON == 1) {

            /**
             * ACIONA REFRIGERACAO EM ALTA RPM (LEBRE)
             */
            if ($telemetria->tele_ent5 == 1) {
                return "{$base}/REFRE_ALTA_RPM.svg";
            }

            if ($telemetria->tele_ent2 == 1) {
                return "{$base}/ALARME.svg";
            }

            /**
             * ACIONA REFRIGERACAO EM BAIXA (TARTARUGA)
             */
            if ($telemetria->tele_ent5 == 0) {
                return "{$base}/REFRI_BAIXA_RPM.svg";
            }

            if ($telemetria->tele_ent7 == 1) {
                return "{$base}/ICONE DEGELO LARANJA.svg";
            }

        }

        /**
         * STANDBY LIGADO
         */
        if ($ignicaoOn == 1 && $motorON == 0) {
            return "{$base}/STANDBY.svg";
        }

        /**
         * STANDBY DESLIGADO
         */
        return "{$base}/DESLIGADO.svg";


    }


    public static function iconesOperacaoContainer($telemetria, $ignicaoOn, $motorON)

    {


        /**
         * Entradas do leitor de container
         * AZUL ->  IGNICAO
         * AMARELO -> DINJUNTOR
         * VERDE -> ACELERACAO
         *
         */


        $base = '/img/rastreamento/icones/OPERACAO/CONTAINERS';

//        exit('aki');
        /**
         * EQUIPAMENTO LIGADO E MOTOR LIGADO
         */
//        if ($ignicaoOn == 1) {

        /**
         * ACIONA REFRIGERACAO EM ALTA RPM (LEBRE)
         */
        if ($telemetria->tele_ent1 == 1 && $telemetria->tele_ent2 == 1 && $telemetria->tele_ent3 == 1) {

//            exit('aki');
            return "{$base}/ligado_alta.svg";
        }

        if ($telemetria->tele_ent1 == 1 && $telemetria->tele_ent2 == 0 && $telemetria->tele_ent3 == 1) {
            return "{$base}/ligado_baixa.svg";
        }

        if ($telemetria->tele_ent1 == 1 && $telemetria->tele_ent2 == 0 && $telemetria->tele_ent3 == 0) {
            return "{$base}/ligado_baixa.svg";
        }



        if ($telemetria->tele_ent2 == 1) {
            return "{$base}/ligado_falha_entrada.svg";
        }



//        }

        /**
         * STANDBY DESLIGADO
         */
        return "{$base}/desligado.svg";


    }

}
