<?php

namespace App\Models;
use MongoDB\Laravel\Eloquent\Model;

class UsuariosPerfiles extends Model
{
    //
    protected $connection = "mongodb";
    protected $table="UsuariosPerfiles";
    protected $primaryKey = '_id';
    protected $keyType = 'string';
    protected $fillable = [
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'licenciatura',
        'consultorio',
        'no_cuenta',
        'usuarioId',
        'codigo',
    ];

    public function Dependencia (){
        return $this->belongsTo(DependenciasSS::class,'consultorio','_id');
    }
}
