<?php

namespace App\E;

use App\Models\Geral\Recursos;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Rees\Sanitizer\Sanitizer;

class Sistema
{

    public static function agi($config, $acao, Request $eq)
    {
        $campos = [];
        $inptData = [];
        $filtros = [];
        $validacao = [];
        foreach ($config as $cfg) {
            $nomeCampo = $cfg['campo'];
            $campos[] = $nomeCampo;

            $inptData[$nomeCampo] = $eq->get($nomeCampo, null);
            $auxFiltro = Arr::get($cfg, 'filtros');
            if ($inptData[$nomeCampo] !== null && $auxFiltro) {
                $filtros[$nomeCampo] = $auxFiltro;
            }

            $validateRule = Arr::get($cfg, "acoes.{$acao}");
            if ($validateRule) $validacao[$nomeCampo] = $validateRule;
        }

        $sanitir = new Sanitizer();

        $sanitir->register('number_int', function ($value) {
            return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
        });

        $sanitir->register('digitos', function ($value) {

            return preg_replace('~\D~', '', $value);

        });

        $sanitir->register('empty2null', function ($value) {
            if ($value === '') return null;
            return $value;
        });

        $sanitir->sanitize($filtros, $inptData);


        foreach ($inptData as $key => $valor) {
            if (is_string($valor) && trim($valor) == '') {
                $inptData[$key] = null;
            }
        }

        return new Fluent([
            'validacao' => $validacao,
            'dados' => $inptData
        ]);
    }


    public static function controleAcessoRecursos()
    {

        $recursos = Recursos::all();

        $deepMap = [];

        foreach ($recursos as $rc) {
            Arr::set($deepMap, $rc->hierarquia, $rc);
        }

        return $deepMap;

    }
}
