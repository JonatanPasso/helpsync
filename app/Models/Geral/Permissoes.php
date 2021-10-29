<?php

namespace App\Models\Geral;

use Illuminate\Database\Eloquent\Model;

class Permissoes extends Model
{
    protected $table = 'geral_permissoes';

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


    public function recurso()
    {
        return $this->hasOne(Recursos::class, 'id', 'recurso_id');
    }


}
