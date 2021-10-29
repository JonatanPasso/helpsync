<?php

namespace App\Models\Geral;

use App\Models\Frota\Veiculos;
use App\Models\Frota\VeiculosXClientes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $table = 'geral_clientes';

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


    public function setCadastradoEmAttribute($value)
    {
        if ($value) {
            $this->attributes['cadastrado_em'] = Carbon::
            createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }

    }

    public function getCadastradoEmAttribute($value)
    {

        if ($value)
            return Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
    }

    public function usuarios()
    {

        return $this->hasManyThrough(
            Usuarios::class,
            UsuariosXclientes::class,
            'cliente_id',
            'id',
            'id', 'usuario_id');
    }

    public function veiculos()
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
            Veiculos::class,
            VeiculosXClientes::class,
            'cliente_id',
            'id',
            'id', 'veiculo_id');
    }

    public function filiais()
    {
        return $this->hasMany(Clientes::class, 'empresa_matriz_id', 'id');
    }

    public function departamentos()
    {
        return $this->hasOne(Departamentos::class, 'empresa_id', 'id')
            ->whereNull('departamento_pai_id');

    }

    public function departamentos2()
    {
        return $this->hasMany(Departamentos::class, 'empresa_id', 'id');
    }

}
