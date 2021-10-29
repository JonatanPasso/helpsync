<?php

namespace App\Models\Contrato;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Aulas
 *
 * @package App\Models\Contrato
 * @mixin \Eloquent
 */
class ContratoAnexos extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'contrato_anexos';

    public $timestamps = false;

}

