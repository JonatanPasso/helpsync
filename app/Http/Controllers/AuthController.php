<?php

namespace App\Http\Controllers;

use App\E\Util;
use App\Models\Geral\Usuarios;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use \Auth;

class AuthController extends Controller
{
    private $cookeName = 'authorization';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    //
    public function index($nomeCliente = null)
    {

        return view('home.login');
    }

    private function loginIsCpf($login)
    {
        return strlen(preg_replace('~\D~', '', $login)) === 11;
    }

    private function loginIsTelefone($login)
    {
        $size = strlen(preg_replace('~\D~', '', $login));
        return in_array($size, [10, 11]);
    }

    private function loginIsEmail($login)
    {
        return Str::contains($login, "@");
    }

    public function postLogin(Request $req)
    {


        if (\Auth::check()) {

            return redirect('/');
        }

        $validator = app('validator')->make($req->all(), [
            'login' => 'required',
            'senha' => 'required|min:4|max:20'
        ], []);

        if ($validator->fails()) {
            return $this->returnLoginViewErro('Credenciais não informadas corretamente');
        }
        $queryLogin = Usuarios::query();

        $queryLogin->where('senha', $req->senha);


        if ($this->loginIsTelefone($req->login) || $this->loginIsCpf($req->login)) {
            $queryLogin->where(function ($query) use ($req) {
                $query->where('fone', $req->login);
                $query->orWhere('cpf', $req->login);
            });

        } else if ($this->loginIsEmail($req->login)) {
            $queryLogin->where('email', $req->login);
        } else {
            $queryLogin->where('usuario', $req->login);
        }

        $usuario = $queryLogin->first();

        if ($usuario && $usuario->status == 'ATIVO') {

            $token = Str::random(32);

            $client_host = Str::limit(Util::get_client_ip(), 255);
            $reverse_dns = ($client_host && $client_host != 'UNKNOWN') ? gethostbyaddr($client_host) : null;

            app('db')
                ->table('geral_tokens')
                ->insert([
                    'token' => $token,
                    'user_id' => $usuario->id,
                    'client_host' => "{$client_host} | {$reverse_dns}",
                    'browser_user_agent' => Str::limit($req->header('User-Agent'), 400),
                    'criado_em' => Carbon::now(),
                ]);

            $cookie = new Cookie($this->cookeName, $token);

            return redirect('/')->withCookie($cookie);
        }

        return $this->returnLoginViewErro('Usuário o senha inválidos');


        //    $rules = validator($req->all(),[]);
    }

    private function returnLoginViewErro($msg)
    {
        return view('home.login')->with('erro', $msg);
    }

    public function sair(Request $request)
    {
        $token = $request->cookie($this->cookeName);

        if ($token) {

            app('db')
                ->table('geral_tokens')
                ->where('token', $token)
                ->where('status', 'ATIVO')
                ->update([
                    'valido_ate' => Carbon::now(),
                    'status' => 'LOGOFF'
                ]);
        }

        $cookie = new Cookie($this->cookeName, null, Carbon::now()->subYears(99));

        return redirect('/')->withCookie($cookie);
    }
}
