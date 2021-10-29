<?php

namespace App\Http\Controllers\Frota;

use App\E\Mensagens;
use App\Http\Controllers\Controller;
use App\Models\Frota\Eventos;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;

class EventosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function salvar(Request $req)
    {

        $eventos[] = Eventos::IGNICAO;
        $eventos[] = Eventos::KM;
        $eventos[] = Eventos::LIGADO_E_PARADO;
        $eventos[] = Eventos::VELOCIDADE;

        $aux = $req->all();

        if (@$aux['vel_min']) {
            $aux['vel_min'] = (int)$aux['vel_min'];
        }
        if (@$aux['vel_max']) {
            $aux['vel_max'] = (int)$aux['vel_max'];
        }

        /**
         * @var Validator $validator
         */
        $validator = app('validator')->make($aux, [
            'id' => 'integer',
            'evento' => 'required|in:' . join(',', $eventos),
            'veiculo_id' => 'required:exist:frota_veiculos,id',

            'vel_min' => 'min:1|max:999|lt:vel_max|required_if:evento,' . Eventos::VELOCIDADE,
            'vel_max' => 'min:1|max:999|gt:vel_min|required_if:evento,' . Eventos::VELOCIDADE,

            'tempo' => 'min:1|max:99999|required_if:evento,' . Eventos::LIGADO_E_PARADO,
            'valor_km' => 'min:1|max:999999|required_if:evento,' . Eventos::KM,
        ], [
            'vel_min.lt' => 'deve ser menor que velocidade máxima',
            'vel_max.gt' => 'deve ser maior que velocidade mínima',
        ]);

        if ($validator->fails()) {
            return response($validator->errors(), 400);
        }


        $registro = Eventos::where('id', (int)$req->id)->first();

        if ($registro) {

        } else {
            $registro = new Eventos();
        }

        $registro->evento = $req->evento;
        $registro->veiculo_id = $req->veiculo_id;

        $registro->vel_min = null;
        $registro->vel_max = null;
        $registro->valor_km = null;
        $registro->tempo = null;

        if ($req->evento == Eventos::VELOCIDADE) {
            $registro->vel_min = $req->vel_min;
            $registro->vel_max = $req->vel_max;
        }

        if ($req->evento == Eventos::KM) {
            $registro->valor_km = $req->valor_km;
        }

        if ($req->evento == Eventos::LIGADO_E_PARADO) {
            $registro->tempo = $req->tempo;
        }

        if (!$registro->isDirty()) {
            return response(Mensagens::ERRO_DB_NOT_UPDATE, 421);
        }

        $registro->save();


        return $registro;


    }

    public function excluir(Request $req)
    {

        $registro = Eventos::where('id', (int)$req->id)->first();

        if ($registro) {

//            if ($registro->count()) {
//                return response(Mensagens::ERRO_DB_DELETE, 421);
//            }

            $registro->delete();

            return response(['msg' => Mensagens::OK_DB_DELETE]);

        }

        return response(Mensagens::ERRO_DB_FIND, 421);


    }

    public function buscar(Request $req)
    {

        $query = Eventos::query();
        //wheres

        if ($req->input('filtro.veiculo_id')) {
            $query->where('veiculo_id', (int)$req->input('filtro.veiculo_id'));
        }

        //orders
        //includes


        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao == 0) {
            return $query->get();
        }
        return $query->paginate(100);
    }
}
