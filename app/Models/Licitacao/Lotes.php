<?php

namespace App\Models\Licitacao;

use App\Models\Atividades\Atividades;
use App\Models\Contrato\PreContrato;
use App\Models\Geral\Clientes;
use App\Models\Geral\Notificacoes;
use App\Models\Geral\Usuarios;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Aulas
 *
 * @package App\Models\Licitacao
 * @mixin \Eloquent
 */
class Lotes extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'licitacao_lotes';

    public $timestamps = false;

    public function licitacao()
    {
        return $this->hasOne(Licitacao::class, 'id', 'licitacao_id');
    }

    public function fornecedores()
    {
        return $this->hasOne(Clientes::class, 'id', 'cliente_id');
    }
}

