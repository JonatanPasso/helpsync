<?php

namespace App\Models\Geral;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Geral\FileStorage
 *
 * @property mixed $metadata
 * @property-read mixed $original_name
 * @property-read mixed $url
 * @mixin \Eloquent
 * @property int $id
 * @property string $uid
 * @property string $path
 * @property string $status
 * @property string $created_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Geral\FileStorage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Geral\FileStorage whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Geral\FileStorage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Geral\FileStorage whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Geral\FileStorage wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Geral\FileStorage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Geral\FileStorage whereUid($value)
 */
class FileStorage extends Model
{

    # public $incrementing = true;

    protected $primaryKey = 'id';

    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //protected $hidden = [];

    protected $table = 'geral_file_storage';

    protected $appends = ['url', 'original_name'];

    public function getUrlAttribute()
    {
        return url("/api/geral/file-storage/get/{$this->uid}");
    }

    public function setAsProduction()
    {
        $this->status = 'prodution';
    }

    public function getOriginalNameAttribute($value)
    {
        $data = $this->metadata;

        if ($data) {
            return $data['originalName'];
        }
        return null;
    }

    public function setMetadataAttribute($value)
    {
        $this->attributes['metadata'] = json_encode($value);
    }

    public function getMetadataAttribute($value)
    {
        return @json_decode($value, true);
        //  return json_decode($value);
    }


    public static function boot()
    {

        parent::boot();

        /*
         * Remover arquivo do sistema de arquivos ao ser deletado o banco de dados
         */
        FileStorage::deleting(function ($file) {
            @unlink($file->path);
        });

        FileStorage::creating(function ($instance) {
            $instance->status = 'temporary';
            return $instance;
        });

        /**
         * Excluir arquivos temporarios depois timeout
         */
        FileStorage::where('status', 'temporary')
            ->where('created_at', '<=', Carbon::now()->subMinute(env('FILESTORAGE_TIMEOUT', 15)))
            ->each(function ($file) {
                $file->delete();
            });

    }

    public static function getTempFileUid($uid)
    {

        return self::whereUid($uid)
            ->where('status', 'temporary')
            ->first();
    }

    public static function getFileUid($uid)
    {
        return self::whereUid($uid)
            ->first();
    }

    public function createdBy()
    {
        return $this->hasOne(Usuarios::class, 'id', 'created_by');
    }

}
