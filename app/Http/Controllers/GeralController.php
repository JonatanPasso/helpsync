<?php

namespace App\Http\Controllers;

use App\E\Sistema;
use App\E\Util;
use App\Models\Geral\Cidades;
use App\Models\Geral\Permissoes;
use App\Models\Geral\Recursos;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Lumen\Http\Request;

class GeralController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $usuarioLogado = \Auth::user();

        $clientes = $clientes = $this->getClientes();

        $clienteAtivo = $this->getClienteAtivo();

        \Illuminate\Support\Facades\View::share('USER', $usuarioLogado);
        \Illuminate\Support\Facades\View::share('CLIENTE', $clienteAtivo);
        \Illuminate\Support\Facades\View::share('CLIENTES', $clientes);

    }

    public function index()
    {

        return view('home.index')->with('infoSession', $this->getSessionData());
    }


    public function indexView($modulo = 'geral', $tela = 'painel')
    {

        $modulo = Str::slug(Str::lower($modulo));
        $tela = Str::slug(Str::lower($tela));
        $viewFile = "{$modulo}.{$tela}";

        $view = view($viewFile);

        return $view;

    }

    private function getSessionData()
    {

        $usuarioLogado = \Auth::user();


        $clienteAtivo = $this->getClienteAtivo();


        $queryPermissoes = Permissoes::query();

        $queryPermissoes->orWhere(function ($query) use ($usuarioLogado, $clienteAtivo) {
            $query->where('usuario_id', (int)$usuarioLogado->id);
            if ($clienteAtivo) {
                $query->where('empresa_id', (int)$clienteAtivo->id);
            }
        });

        if ($usuarioLogado->gruposUsuarios->count()) {
            $queryPermissoes->orWhere(function ($query) use ($usuarioLogado) {
                $query->whereIn('grupos_usuarios_id',
                    $usuarioLogado->gruposUsuarios->pluck('id')->toArray());
            });
        }
//        var_dump($queryPermissoes->getBindings());
//        exit;

        //  return $queryPermissoes->toSql();

        $queryPermissoes->with('recurso');

        $listaAcesso = $queryPermissoes->get();

        $menuItens = Recursos::query()->where('tipo', 'MENU')
            ->get()
            ->reduce(function ($arr, Recursos $item) use ($listaAcesso, $usuarioLogado) {

                $permisao = $listaAcesso->firstWhere('recurso_id', $item->id);

                if ($usuarioLogado->is_root == 'Y') {
                    $item->has_p = 'Y';
                } else {
                    $item->has_p = $permisao ? $permisao->acesso : 'N';
                }

                $arr[$item->modulo][$item->grupo][$item->nome] = $item;
                return $arr;
            }, []);


        return [

            'USER' => $usuarioLogado,
            'CLIENTE' => $clienteAtivo,
            'menu' => $menuItens,
            'permissoes' => $listaAcesso
        ];
    }

    public function infoSessao()
    {

        return $this->getSessionData();
    }
}
