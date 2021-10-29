<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Geral\Clientes;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {


    }

    protected function getUsuario()
    {
        return \Auth::user();
    }

    protected function getClientes()
    {

        $usuarioLogado = $this->getUsuario();

        if ($usuarioLogado->is_root == 'Y') {
            $clientes = Clientes::all();
        } else {

            $clientes = $usuarioLogado->clientes;
        }

        return $clientes;
    }

    protected function getClienteAtivo()
    {

        $usuarioLogado = \Auth::user();

        $requeset = app('request');

        $clientes = $this->getClientes();

        $clienteAtivo = $clientes->first();

        if ((int)$requeset->cliente_id) {
            foreach ($clientes as $cli) {
                if ($cli->id == $requeset->cliente_id) {
                    $clienteAtivo = $cli;
                    break;
                }
            }
        }

        return $clienteAtivo;

    }

    protected function doValidation($req, $agi, $ignoreNull = false)
    {
        $data = [];
        if ($req instanceof Request) {
            $data = $req->all();
        }

        if ($ignoreNull) {
            foreach ($data as $key => $value) {
                if ($value === null || is_string($value) && trim($value) == '') {
                    unset($data[$key]);
                }
            }
        }

        $validator = app('validator')
            ->make($data, $agi['validacao']);


        if ($validator->fails()) {

            throw new ValidationException($validator);

            // return response($validator->errors(), 400);
        }

        return false;
    }
}
