<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use App\Models\UsuariosPerfiles;
use App\Models\Roles;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $connection = "mongodb";
    protected $table="users";
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    public function getAuthIdentifierName()
    {
        return '_id'; // o el campo que uses como identificador
    }

    public function perfil(){
        return $this->hasOne(UsuariosPerfiles::class,'usuarioId','_id');
    }

    public function EtiquetaRol (){
        return $this->belongsTo(Roles::class,'rol','valor');
    }

    protected $fillable = [
        'email',
        'nameUser',
        'rol',
        'activo',
        'created_by',
        'status',
        'acceso_permitido',
        'observaciones'
    ];

}
