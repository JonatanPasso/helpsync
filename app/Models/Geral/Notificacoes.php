<?php

namespace App\Models\Geral;

use App\Models\Geral\Usuarios;
use Illuminate\Database\Eloquent\Model;

class Notificacoes extends Model
{
    protected $table = 'geral_notificacoes';

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


    public function usuario()
    {
        return $this->hasOne(Usuarios::class, 'id', 'usuario_id');
    }

    public static function registrarNotificacao($titulo, $msg, $idUsuarioDestino)
    {

        $usuario = Usuarios::where('id', $idUsuarioDestino)->first();

        if ($usuario) {
            $notificacao = new Notificacoes();
            $notificacao->usuario_id = $idUsuarioDestino;
            $notificacao->titulo = $titulo;
            $notificacao->msg = $msg;
            $notificacao->status = 'P';
            $notificacao->save();
        }
    }
}
