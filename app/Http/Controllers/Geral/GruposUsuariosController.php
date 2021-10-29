<?php

namespace App\Http\Controllers\Geral;

use App\E\Mensagens;
use App\E\Util;
use App\Http\Controllers\Controller;
use App\Models\Geral\GruposUsuarios;
use App\Models\Geral\GruposUsuariosXusuarios;
use App\Models\Geral\Permissoes;
use App\Models\Geral\Usuarios;
use App\Models\Geral\UsuariosXclientes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class GruposUsuariosController extends Controller
{

    public function buscar(Request $req)
    {

        $usuarioLogado = $this->getUsuario();

        $result = null;

        $query = GruposUsuarios::query();

        // $query->where('usuario_id', $usuarioLogado->id);


        $query->orderBy('id', 'DESC');
        //wheres
        //orders
        if ($req->include && is_array($req->include)) {
            foreach ($req->include as $include) {
                $query->with($include);
            }
        }

        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {
            $result = $query->get();
        } else {
            $result = $query->paginate(100);
        }

        return $result;
    }

    public function buscarUsuarios(Request $req)
    {

        $grupo = GruposUsuarios::query()
            ->where('id', (int)$req->grupos_usuarios_id)
            ->first();

        if (!$grupo) {
            return response(Mensagens::ERRO_DB_FIND, 421);
        }

        $query = $grupo->usuarios();

        $query->orderBy('nome', 'asc');
        //wheres
        //orders
        if ($req->include && is_array($req->include)) {
            foreach ($req->include as $include) {
                $query->with($include);
            }
        }

        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {
            $result = $query->get();
        } else {
            $result = $query->paginate(100);
        }

        return $result;

    }

    public function adicionarUsuario(Request $req)
    {

        if ($req->input('modo') == 'incluir_todos') {

            return $this->adicionarTodosUsuarios($req);
        }

        $jaCadastrado = GruposUsuariosXusuarios::query()
            ->where('grupos_usuarios_id', (int)$req->grupos_usuarios_id)
            ->where('usuario_id', $req->usuario_id)
            ->first();

        if ($jaCadastrado) {
            return response(Mensagens::ERRO_DB_DUPLICADO, 421);
        }

        $registro = new GruposUsuariosXusuarios();
        $registro->grupos_usuarios_id = $req->grupos_usuarios_id;
        $registro->usuario_id = $req->usuario_id;
        $registro->criado_em = Carbon::now();
        $registro->criado_por = $this->getUsuario()->id;

        $registro->save();

        return $registro;
    }

    public function adicionarTodosUsuarios(Request $req)
    {

        app('db')->beginTransaction();

        GruposUsuariosXusuarios::query()
            ->where('grupos_usuarios_id', $req->grupos_usuarios_id)
            ->delete();

        $todosUsuarios = Usuarios::all();

        $aux = [];
        foreach ($todosUsuarios as $usuario) {

            $temp = [];
            $temp['grupos_usuarios_id'] = $req->grupos_usuarios_id;
            $temp['usuario_id'] = $usuario->id;
            $temp['criado_em'] = Carbon::now();
            $temp['criado_por'] = $this->getUsuario()->id;

            $aux[] = $temp;
        }


        GruposUsuariosXusuarios::query()->insert($aux);

        app('db')->commit();

        return response(['msg' => Mensagens::OK_DB_MASS_INSERT]);
    }

    public function excluirTodosUsuarios(Request $req)
    {

        GruposUsuariosXusuarios::query()
            ->where('grupos_usuarios_id', $req->grupos_usuarios_id)
            ->delete();

        return response(['msg' => Mensagens::OK_DB_MASS_INSERT]);
    }


    public function excluirUsuario(Request $req)
    {

        if ($req->input('modo') == 'excluir_todos') {

            return $this->excluirTodosUsuarios($req);
        }

        $registro = GruposUsuariosXusuarios::query()
            ->where('grupos_usuarios_id', (int)$req->grupos_usuarios_id)
            ->where('usuario_id', $req->usuario_id)
            ->first();

        if (!$registro) {
            return response(Mensagens::ERRO_DB_DELETE, 421);
        }

        $registro->delete();

        return response(['msg' => 'Registro removido com sucesso']);
    }

    public function salvar(Request $req)
    {

        $custonMsg = [
            'usuarios.required' => 'Pelo menus um usuário deve ser informado.'
        ];

        $validator = app('validator')
            ->make($req->all(), [
                'id' => 'int',
                'nome' => 'required|min:1|max:100',
                'cliente_id' => 'int'], $custonMsg);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }

        if ($req->input('id')) {
            $registro = GruposUsuarios::where('id', $req->id)->first();
        } else {
            $registro = new GruposUsuarios();
            $registro->criado_em = Carbon::now();
        }

        $registro->nome = Str::upper(trim($req->nome));
        $registro->cliente_id = (int)$req->cliente_id ? $req->cliente_id : null;

        app('db')->beginTransaction();

        $atualizar = $registro->isDirty();

        if ($atualizar) $registro->save();

        if (!$atualizar) {
            return response(Mensagens::ERRO_DB_NOT_UPDATE, 421);
        }

        app('db')->commit();

        return $registro;

    }


    public function excluir(Request $req)
    {

        $registro = GruposUsuarios::where('id', (int)$req->id)->first();

        app('db')->beginTransaction();

        if ($registro) {


            GruposUsuariosXusuarios::where('grupos_usuarios_id', $registro->id)
                ->delete();

            $registro->delete();

            app('db')->commit();

            return response(['msg' => 'Registro removido com sucesso']);
        }


        return response('Registro não encontrado.', 421);


    }


}
