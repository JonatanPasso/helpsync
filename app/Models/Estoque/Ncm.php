<?php

namespace App\Models\Estoque;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;


/**
 * Class Ncm
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property text ncm
 * @property text categoria
 * @property text descricao
 * @property text ipi
 * @property text inicio_vigencia
 * @property text unidade_tributacao
 * @property text desc_unidade_tribut
 * @property text gtin_producao
 * @property text gtin_homologacao
 * @property text obs
 */
class Ncm extends Model
{
    protected $table = 'estoque_ncm';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    public $timestamps = false;

    public function produtos()
    {
        return $this->hasMany(Produtos::class, 'ncm', 'ncm');
    }
}
