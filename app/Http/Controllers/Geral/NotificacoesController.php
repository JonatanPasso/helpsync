<?php

namespace App\Http\Controllers\Geral;

use App\E\Mensagens;
use App\Events\Event;
use App\Http\Controllers\Controller;
use App\Models\Frota\Eventos;
use App\Models\Geral\Notificacoes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class NotificacoesController extends Controller
{

    public function buscar(Request $req)
    {

        $usuarioLogado = $this->getUsuario();

        /**
         * @var Collection $configMobile
         */
//        $configMobile = $usuarioLogado->configs()
//            ->where('key', 'like', "%mobile")
//            ->where('VALUE', "NAO")
//            ->get()
//            ->pluck('key')
//            ->toArray();
//
//        $map = [
//            'alerta_ignicao_mobile' => Eventos::IGNICAO,
//            'alerta_velocidade_mobile' => Eventos::VELOCIDADE,
//            'alerta_ligado_parado_mobile' => Eventos::LIGADO_E_PARADO,
//        ];
//
//        $filtro = array_values(Arr::only($map, $configMobile));


        $result = null;

        $query = Notificacoes::query();

        $query->where('usuario_id', $usuarioLogado->id);

        $query->orderBy('id', 'DESC');

        //wheres
        //orders
        if ($req->include && is_array($req->include)) {
            foreach ($req->include as $include) {
                $query->with($include);
            }
        }

        if ($req->check_new == 'true') {

            $query->whereNull('entregue_em');

//            if ($filtro) $query->whereNotIn('titulo', $filtro);
        }

        if ($req->check_read == 'true') {
            $query->whereNull('leitura_em');
        }

        if ((int)$req->id) {
            $query->whereId((int)$req->id);
        }

        if ($req->paginacao && $req->paginacao === 'false' || $req->paginacao === 0) {
            $result = $query->get();
        } else {
            $result = $query->paginate(100);
        }


        if ($req->check_new == 'true') {
            $query->update(['entregue_em' => Carbon::now()]);
        }

        return $result;
    }

    public function visualizar(Request $req)
    {

//        sleep(10);
        $query = Notificacoes::query();

        $notificacao = $query
            ->whereIn('id', (array)$req->id)
            ->where('usuario_id', $this->getUsuario()->id);

        if ($notificacao) {

            $notificacao->update([
                'leitura_em' => Carbon::now()
            ]);

            return $notificacao->get();
        }

        return response(Mensagens::ERRO_DB_FIND);

    }


}
