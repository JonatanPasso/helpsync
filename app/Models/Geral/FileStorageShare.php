<?php

namespace App\Models\Geral;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FileStorageShare
 * @package App\Models\Geral
 *
 * @property int id Share id
 * @property int $file_id File Id
 * @property int $share_cliente_id Empresa
 * @property int $share_to_user_id Id usuario compartilhado
 * @property Carbon|null $share_timeout Tempo validade compartilhamento
 * @property String $share_token Token do Compartilhamento
 * @property String $comment Comentario
 * @property int $share_by_user_id Id usuario que compartilhou
 * @property Carbon $share_at Compartilhado em
 */
class FileStorageShare extends Model
{
    protected $table = 'geral_file_storage_share';

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

    public function shareBy()
    {
        return $this->hasOne(Usuarios::class, 'id', 'share_by_user_id');
    }


    public function shareTo()
    {
        return $this->hasOne(Usuarios::class, 'id', 'share_to_user_id');
    }


    public function cliente()
    {
        return $this->hasOne(Clientes::class, 'id', 'share_by_user_id');
    }

    public function fileStorage()
    {
        return $this->hasOne(FileStorageShare::class, 'id', 'file_id');
    }


}
