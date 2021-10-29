<?php

namespace App\Models\Geral;

use Illuminate\Database\Eloquent\Model;

class GruposUsuariosXusuarios extends Model
{
    protected $table = 'geral_grupos_usuarios_x_usuarios';

    public $timestamps = false;


    public function grupoUsuario()
    {
        return $this->hasOne(GruposUsuarios::class, 'id', 'grupos_usuarios_id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuarios::class, 'id', 'usuario_id');
    }

}
