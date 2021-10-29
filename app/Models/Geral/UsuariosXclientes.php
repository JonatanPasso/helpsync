<?php

namespace App\Models\Geral;

use Illuminate\Database\Eloquent\Model;

class UsuariosXclientes extends Model
{
    protected $table = 'global_usuarios_x_clientes';

    public $timestamps = false;

    public $fillable = ['cliente_id', 'usuario_id'];

    public function responsavel()
    {
        return $this->hasOne(Usuarios::class,'id', 'usuario_id');
    }

}
