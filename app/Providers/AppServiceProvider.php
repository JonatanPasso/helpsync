<?php

namespace App\Providers;

use App\E\CustonValidator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Validator::resolver(function($translator, $data, $rules, $messages)
        {

            return new CustonValidator($translator, $data, $rules, $messages);
        });
    }
}
