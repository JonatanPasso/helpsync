<?php

namespace App\Models\Geral;

use App\Models\Geral\Departamentos;
use App\Models\Geral\Clientes;
use App\Models\Geral\VagaDepartamento;
use http\Env\Request;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;

class Usuarios extends Model implements AuthenticatableContract, AuthorizableContract
{
    protected $table = 'geral_usuarios';

    public $timestamps = false;

    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'senha',
    ];

    public function clienteAtivo()
    {

    }

    public function clientes()
    {

        /*
         *   'App\Post',
            'App\User',
            'country_id', // Foreign key on users table...
            'user_id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'id' // Local key on users table...
         */
        return $this->hasManyThrough(
            Clientes::class,
            UsuariosXclientes::class,
            'usuario_id',
            'id',
            'id', 'cliente_id');
    }

    public function getApelidoAttribute($valor)
    {
        if ($valor) {
            return $valor;
        }
        return Str::words($this->nome, 1, '');
    }

    public function permissoes()
    {
        return $this->hasMany(Permissoes::class, 'usuario_id', 'id');
    }

    public function hasP($permisao)
    {

        if ($this->is_root == 'Y') {
            return true;
        }

        $recursos = $this->permissoes()
            ->where('acesso', 'Y')
            ->where('usuario_id', $this->id)
            ->with('recurso')
            ->get();

        foreach ($recursos as $r) {
            if ($permisao && $r->recurso->uid === $permisao) {
                return true;
            }

        }

        return false;
    }

    public function hasH($hierarquia)
    {


        if ($this->is_root == 'Y') {
            return true;
        }

        $recursos = $this->permissoes()
            ->where('usuario_id', $this->id)
            ->where('acesso', 'Y')
            ->with('recurso')
            ->get();


        foreach ($recursos as $r) {

            if (strpos(Str::lower($r->recurso->hierarquia), Str::lower($hierarquia)) === 0) {

                return true;
            }

        }
        return false;
    }

    public function vagaDepartamentos()
    {
        return $this->hasMany(VagaDepartamento::class, 'usuario_id', 'id');
    }


    public function gruposUsuarios()
    {

        /*
         *   'App\Post',
            'App\User',
            'country_id', // Foreign key on users table...
            'user_id', // Foreign key on posts table...
            'id', // Local key on countries table...
            'id' // Local key on users table...
         */
        return $this->hasManyThrough(
            GruposUsuarios::class,
            GruposUsuariosXusuarios::class,
            'usuario_id',
            'id',
            'id', 'grupos_usuarios_id');
    }

}
