<?php

namespace App\Http\Controllers\Geral;

use App\E\Dic\Geral;
use App\E\Sistema;
use App\Http\Controllers\Controller;
use App\Models\Geral\Departamentos;
use App\Models\Geral\FileStorage;
use App\Models\Geral\Usuarios;
use App\Models\Geral\UsuariosXclientes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UsuariosController extends Controller
{

    public function salvarPerfil(Request $req)
    {

        $this->validate($req, [
            'id' => 'int',
            'cpf' => "cpfcnpj|unique:geral_usuarios,cpf,{$req->id},id",
            'email' => "email|unique:geral_usuarios,email,{$req->id},id",
            'fone' => "unique:geral_usuarios,fone,{$req->id},id",
            'usuario' => "required|unique:geral_usuarios,usuario,{$req->id},id",
            'nome' => 'required',
            'senha' => 'min:4|max:20',
            'genero' => 'in:MASCULINO,FEMININO,NÃO INFORMADO'
        ]);

        $usuario = $this->getUsuario();


        $usuario->nome = Str::upper($req->nome);
        $usuario->cpf = $req->cpf;
        $usuario->email = Str::lower($req->email);
        $usuario->fone = $req->fone;
        $usuario->genero = $req->genero;

        if ($req->senha)
            $usuario->senha = $req->senha;

        $usuario->modo_noturno = $req->modo_noturno;

        if ($req->input('foto_upload_id') &&
            $fileFotoUpload = FileStorage::query()
                ->where('uid', $req->foto_upload_id)
                ->first()) {

            $fileFotoUpload->setAsProduction();
            $fileFotoUpload->save();

            $usuario->url_foto = $fileFotoUpload->url;

        }

        $usuario->save();


        return $usuario;


    }

    public function salvar(Request $eq)
    {


        $registro = Usuarios::where('id', (int)$eq->id)->first();


        $config = Geral::usuarios($eq);
        $acao = __FUNCTION__;

        $agi = Sistema::agi($config, $acao, $eq);

        $this->doValidation($eq, $agi);


        app('db')->beginTransaction();

        $registro = $registro ? $registro : new Usuarios();

        $aux = $agi['dados'];

        /**
         * Alterar senha somente se mesma for setada
         */
        if ($aux['senha'] === null) {
            unset($aux['senha']);
        }

        unset($aux['is_root']);

        $registro->forceFill($aux);

        if (!$registro->isDirty()) {

            $qtdNovos = $this->vincularUsuarioCliente($registro, $eq);

            if ($qtdNovos) {

                app('db')->commit();

                return $registro;
            } else
                return response('Nada de novo para salvar.', 421);
        }

        $registro->save();

        $this->vincularUsuarioCliente($registro, $eq);

        app('db')->commit();

        return $registro;


    }

    public function excluir(Request $req)
    {

        $registro = Usuarios::where('id', (int)$req->id)->first();

        app('db')->beginTransaction();

        if ($registro) {

            $registro->permissoes()->delete();

            UsuariosXclientes::where('usuario_id', $registro->id)
                ->delete();

            $registro->delete();

            app('db')->commit();

            return response(['msg' => 'Registro removido com sucesso']);
        }


        return response('Registro não encontrado.', 421);


    }

    private function buscaUsuarioComDepartamento($req)
    {
        $query = Usuarios::whereId($req->id);

        if ($req->include) {
            foreach ($req->include as $value) {
                $query->with($value);
            }
        }

        $usuario = $query->first();
        $resposta = $usuario->toArray();

        $resposta['departamentos'] = $usuario->vagaDepartamentos()
            ->with('departamento')
            ->get()
            ->filter(function ($item) use ($req) {

                /**
                 * filtrar somente departamentos da empresa passada no request ($req->cliente_id)
                 */
                if ($item->departamento) {
                    if ($req->input('cliente_id')) {
                        if ($item->departamento->empresa_id == $req->cliente_id) {
                            return true;
                        }
                    } else {
                        return true;
                    }

                }
                return false;
            })
            ->transform(function ($depVaga) {
                return $depVaga->departamento;
            });


        return $resposta;

    }

    public function buscar(Request $req)
    {

        if ($req->id) {

            $query = Usuarios::query();

            $query->whereId($req->id);
        }

        $query = Usuarios::query();
        //wheres
        //orders
        if ($req->include && is_array($req->include)) {
            foreach ($req->include as $include) {
                $query->with($include);
            }
        }

        /**
         * Paramentros filtros
         */
        if ($req->input('filtro.id')) {
            $query->where('id', (int)$req->input('filtro.id'));
        }

        if ($req->input('filtro.email')) {
            $query->where('email', $req->input('filtro.email'));
        }

        if ($req->input('filtro.fone')) {
            $query->where('fone', $req->input('filtro.fone'));
        }

        if ($req->input('filtro.contem')) {
            $query->where('nome', 'like', "%{$req->input('filtro.contem')}%");
        }

        if ($req->input('filtro.vinculo_empresa_id')) {
            $query->whereHas('clientes', function ($q) use ($req) {
                $q->where('cliente_id', $req->input('filtro.vinculo_empresa_id'));
            });
        }

        /**
         * Filtra usuarios que existem em alguma vaga com a empresa especificada
         */
        if ($req->input('filtro.vinculo_departamento')) {
            $departamentosIds = Departamentos::query()
                ->where('empresa_id', $req->input('filtro.vinculo_departamento'))
                ->pluck('id');

            $query->whereHas('vagaDepartamentos', function ($sub) use ($departamentosIds) {
                $sub->whereIn('Departamento_id', $departamentosIds);
            });

            //   $query->groupBy('id');
        }

//        if ($req->input('filtro.ignorar_id')) {
//            $query->noteIn('id', [$req->input('filtro.ignorar_id')]);
//        }

        if ($req->departamentos) {
            return $this->buscaUsuarioComDepartamento($req);
        }

        if ($req->paginate == 'false') {
            return $query->get();
        }
        return $query->paginate(100);
    }

    private function vincularUsuarioCliente(Usuarios $registro, $request)
    {

        $temp = [];

        $inserts = 0;

        foreach ($request->clientes as $i) {

            $aux = UsuariosXclientes::where('cliente_id', $i['id'])
                ->where('usuario_id', $registro->id)
                ->first();

            if (!$aux) {
                $aux = new UsuariosXclientes();
                $inserts++;
            }

            $aux->cliente_id = $i['id'];
            $aux->usuario_id = $registro->id;

            $aux->save();

            $temp[] = $aux->id;

        }

        $deletes = UsuariosXclientes::where('usuario_id', $registro->id)
            ->whereNotIn('id', $temp)
            ->delete();

        return $deletes || $inserts;

    }
}
