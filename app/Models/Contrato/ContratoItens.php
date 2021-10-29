<?php

namespace App\Models\Contrato;

use App\Models\Atividades\Atividades;
use App\Models\Estoque\Produtos;
use App\Models\Geral\Clientes;
use App\Models\Geral\Notificacoes;
use App\Models\Geral\Usuarios;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Aulas
 *
 * @package App\Models\Contrato
 * @mixin \Eloquent
 */
class ContratoItens extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'contrato_itens_contrato';

    public $timestamps = false;

    public static function verificaItens($request)
    {
        return self::query()
            ->where('contrato_id', $request->contrato_id)
            ->where('produto_id', $request->produto_id)
            ->first();
    }

    public function produtos()
    {
        return $this->hasOne(Produtos::class, 'id','produto_id');
    }
}

