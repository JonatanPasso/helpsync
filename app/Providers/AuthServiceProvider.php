<?php

namespace App\Providers;

use App\E\Sistema;
use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {


            $token = $request->cookie('authorization');

            if (!$token) {
                $token = $request->input('authorization');
            }

            if ($token) {
                $checkToken = app('db')
                    ->table('geral_tokens')
                    ->where('token', $token)
                    ->where('status', 'ATIVO')
                    ->first();

                if ($checkToken) {
                    $user = User::where('id', $checkToken->user_id)
                        ->where('status', 'ATIVO')
                        ->first();
                    if ($user) return $user;
                }
            }

//            if ($request->input('api_token')) {
//                return User::where('api_token', $request->input('api_token'))->first();
//            }

        });

//        $clienteAtivo= Sistema::getClienteAtivo();
        //  app('view')->share('clienteAtivo', $clienteAtivo);
    }
}
