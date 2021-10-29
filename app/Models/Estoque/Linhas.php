<?php

namespace App\Models\Estoque;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


/**
 * Class Linhas
 * @package App\Models\Estoque
 *
 * @property int $id
 * @property int marca_id Id da marca
 * @property String nome Nome da linha
 * @property Marcas marca Marca associada a linha
 * @property Collection $produtos Produtos associados a esta linha
 */
class Linhas extends Model
{
    protected $table = 'estoque_linhas';

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

    /**
     * Marca associada a testa linha
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function marca()
    {
        return $this->hasOne(Marcas::class, 'id',
            'marca_id');
    }

    /**
     * Produtos associadas a esta linha
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function produtos()
    {
        return $this->hasMany(Produtos::class, 'linha_id',
            'id');
    }

}
