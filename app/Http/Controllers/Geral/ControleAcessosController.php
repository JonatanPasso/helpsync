<?php

namespace App\Http\Controllers\Geral;

use App\E\Dic\Geral;
use App\E\Sistema;
use App\Http\Controllers\Controller;
use App\Models\Geral\GruposUsuarios;
use App\Models\Geral\Permissoes;
use App\Models\Geral\Recursos;
use App\Models\Geral\Usuarios;
use App\Models\Geral\UsuariosXclientes;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class ControleAcessosController extends Controller
{


    public function salvar(Request $eq)
    {

        $rules = [
            'recurso_id' => 'required',
            'acesso' => 'required|in:Y,N'
        ];

        if ($eq->input('grupos_usuarios_id')) {
            $rules['grupos_usuarios_id'] = 'required';
        } else {
            $rules['usuario_id'] = 'required';
            $rules['empresa_id'] = 'required';
        }

        $validator = app('validator')
            ->make($eq->all(), $rules);

        if ($validator->fails()) {

            return response($validator->errors(), 400);
        }

        $queryPermissao = Permissoes::where('usuario_id', $eq->usuario_id)
            ->where('recurso_id', $eq->recurso_id);

        if ($eq->input('empresa_id')) {

            $queryPermissao->where('empresa_id', $eq->empresa_id);
        }

        if($eq->input('grupos_usuarios_id')){
            $queryPermissao->where('grupos_usuarios_id', $eq->grupos_usuarios_id);
        }


        $registro = $queryPermissao->first();

        $registro = $registro ? $registro : new Permissoes();

        $registro->usuario_id = $eq->usuario_id;
        $registro->recurso_id = $eq->recurso_id;
        $registro->grupos_usuarios_id = $eq->grupos_usuarios_id;
        $registro->empresa_id = (int)$eq->empresa_id ? $eq->empresa_id : null;
        $registro->acesso = $eq->acesso;

        $registro->save();

        return $registro;


    }

//    public function excluir(Request $req)
//    {
//
//        $registro = Usuarios::where('id', (int)$req->id)->first();
//
//        if ($registro) {
//            $registro->delete();
//            return response(['msg' => 'Registro removido com sucesso']);
//        }
//
//        return response('Registro não encontrado.', 421);
//
//
//    }

    public function buscar(Request $req)
    {

        $grupo = null;

        if ($req->input('grupo_id')) {

            $grupo = GruposUsuarios::query()
                ->where('id', $req->grupo_id)
                ->first();
        }

        $usuario = Usuarios::query()
            ->where('id', $req->usuario_id)
            ->first();


        $queryPermissoes = Permissoes::query();


        if ($grupo) {
            $queryPermissoes->where('grupos_usuarios_id', $grupo->id);

        } else {
            $queryPermissoes->where('usuario_id', (int)$req->usuario_id);
            $queryPermissoes->where('empresa_id', (int)$req->empresa_id);
        }

        $permissoes = $queryPermissoes->get();


        $menuItens = Recursos::query()->get()
            ->reduce(function ($arr, $item) {
                $arr[$item->modulo][$item->grupo][$item->nome] = $item;
                return $arr;
            }, []);

        array_walk_recursive($menuItens, function ($recurso, $key) use ($permissoes, $usuario, $grupo) {

            $recurso->acesso = 'N';

            foreach ($permissoes as $p) {
                if ($p->recurso_id == $recurso->id) {

                    if ($grupo) {
                        $recurso->acesso = $p->acesso;
                    } else {
                        $recurso->acesso = $usuario->is_root == 'S' ? 'S' : $p->acesso;
                    }

                    break;
                }
            }

        });


        return ($menuItens);
    }

    public function clear($array)
    {

        if (is_array($array)) {

            $aux = [];
            foreach ($array as $key => $value) {
                $aux[] = $this->clear($value);
            }

            return $aux;
        }

        return $array;

    }

}
