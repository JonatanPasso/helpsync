<?php

namespace App\Models\Geral;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Vagas
 *
 * @package App\Models\Geral
 * @mixin \Eloquent
 * @property int $id
 * @property int $empresa_id
 * @property String $nome
 * @property String $descricao
 */
class Vagas extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'geral_vagas';

    public $timestamps = false;
}