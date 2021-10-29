<?php

namespace App\Models\Geral;

use Illuminate\Database\Eloquent\Model;

class GruposUsuarios extends Model
{
    protected $table = 'geral_grupos_usuarios';

    public $timestamps = false;

    public function usuarios()
    {

        return $this->hasManyThrough(
            Usuarios::class,
            GruposUsuariosXusuarios::class,
            'grupos_usuarios_id',
            'id',
            'id', 'usuario_id');
    }

    public function cliente()
    {
        return $this->hasOne(Clientes::class, 'id', 'cliente_id');
    }

}
