<?php
namespace App\Http\Repositorios;
use App\Models\User;
use App\Models\UsuariosPerfiles;
use Log;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;
class PersonalRepositorio {
    private $statusUsuario = null;
    private $activoUsuario = null;
    private $pipeline = [
        [
            '$lookup'=>[
                'from'=>'UsuariosPerfiles',
                'localField'=>'_id',
                'foreignField'=>'usuarioId',
                'as'=>'Perfil',
                'pipeline'=>[
                    [
                        '$lookup'=>[
                            'from'=>'DependenciasSS',
                            'localField'=>'consultorio',
                            'foreignField'=>'_id',
                            'as'=>'Dependencia'
                        ]
                    ],
                    [
                        '$unwind'=>[
                            'path' => '$Dependencia',
                            'preserveNullAndEmptyArrays' => true
                        ]
                    ]
                ]
            ]
        ],
        [
            '$unwind'=>'$Perfil'
        ],
        [
            '$match'=>[
                'rol'=>3
            ]
        ],
        [
            '$addFields'=>[
                'no_cuenta'=>'$Perfil.no_cuenta',
                'nombre'=>'$Perfil.nombre',
                'licenciatura'=>'$Perfil.licenciatura',
                'primer_apellido'=>'$Perfil.primer_apellido',
                'segundo_apellido'=>'$Perfil.segundo_apellido',
                'nombreMedico'=>[
                        '$concat'=>[
                            ['$ifNull'=>['$Perfil.nombre','']],
                            ' ',
                            ['$ifNull'=>['$Perfil.primer_apellido','']],
                            ' ',
                            ['$ifNull'=>['$Perfil.segundo_apellido','']]
                        ]
                ],
                'fecha_inicio'=>[
                    '$dateToString'=>[
                            'format'=>"%Y-%m-%d",
                            'date'=>[
                                '$toDate'=>'$Perfil.fecha_inicio_promocion'
                            ],
                            'timezone'=> 'America/Mexico_City'
                    ]
                ],
                'fecha_termino'=>[
                    '$dateToString'=>[
                            'format'=>"%Y-%m-%d",
                            'date'=>[
                                '$toDate'=>'$Perfil.fecha_termino_promocion'
                            ],
                            'timezone'=> 'America/Mexico_City'
                    ]
                ],
                'fecha_inicio_promocion'=>[
                    '$dateToString'=>[
                            'format'=>"%d de %b %Y",
                            'date'=>[
                                '$toDate'=>'$Perfil.fecha_inicio_promocion'
                            ],
                            'timezone'=> 'America/Mexico_City'
                    ]
                ],
                'fecha_termino_promocion'=>[
                    '$dateToString'=>[
                            'format'=>"%d de %b %Y",
                            'date'=>[
                                '$toDate'=>'$Perfil.fecha_termino_promocion'
                            ],
                            'timezone'=> 'America/Mexico_City'
                    ]
                ],
                'fecha_solicitud_acceso'=>[
                    '$dateToString'=>[
                            'format'=>"%d de %b %Y",
                            'date'=>[
                                '$toDate'=>'$fecha_solicitud_acceso'
                            ],
                            'timezone'=> 'America/Mexico_City'
                    ]
                ],
                'fecha_acceso_vencimiento'=>[
                    '$dateToString'=>[
                            'format'=>"%d de %b %Y",
                            'date'=>[
                                '$toDate'=>'$fecha_acceso_vencimiento'
                            ],
                            'timezone'=> 'America/Mexico_City'
                    ]
                ],
                'fecha_acceso_inicio'=>[
                    '$dateToString'=>[
                            'format'=>"%d de %b %Y",
                            'date'=>[
                                '$toDate'=>'$fecha_acceso_inicio'
                            ],
                            'timezone'=> 'America/Mexico_City'
                    ]
                ],
                'dependencia'=>'$Perfil.Dependencia.nombre',
                'dependenciaUID'=>'$Perfil.Dependencia._id',
                'observaciones'=>'$observaciones',
            ]
        ],
        [
            '$project'=>[
                '_id'=>1,
                'Perfil'=>0
            ]
        ]
    ];
    private $pipelineTotal = [
        [
            '$lookup'=>[
                'from'=>'UsuariosPerfiles',
                'localField'=>'_id',
                'foreignField'=>'usuarioId',
                'as'=>'Perfil',
            ]
        ],
        [
            '$unwind'=>'$Perfil'
        ],
        [
            '$addFields'=>[
                'nombreMedico'=>[
                        '$concat'=>[
                            ['$ifNull'=>['$Perfil.nombre','']],
                            ' ',
                            ['$ifNull'=>['$Perfil.primer_apellido','']],
                            ' ',
                            ['$ifNull'=>['$Perfil.segundo_apellido','']]
                        ]
                ],
            ]
        ],
        [
            '$match'=>[
                'rol'=>3
            ]
        ],
    ];


    public function setEstadoUsuarios($estado){
        switch ($estado) {
            case 'importados':
                return $this->getImportados();
                break;
            case 'pendientes':
                return $this->getPendientes();
                break;
            case 'autorizados':
                return $this->getAutorizados();
                break;
            case 'suspendidos':
                return $this->getSuspendidos();
                break;
            
            default:
                return null;
                break;
        }
    }

    public function getImportados(){
        $this->statusUsuario = null;
        $this->activoUsuario = null;
        return $this;
    }
    public function getPendientes(){
        $this->statusUsuario = [2];
        $this->activoUsuario = [0];
        return $this;
    }
    public function getAutorizados(){
        $this->statusUsuario = [3];
        $this->activoUsuario = [1];
        return $this;
    }
    public function getSuspendidos(){
        $this->statusUsuario = [0];
        $this->activoUsuario = [0];
        return $this;
    }
    

    public function SeleccionarTodosPorEstado(){
        $seleccionados = [];
        $statusUsuario = $this->statusUsuario;
        $activoUsuario = $this->activoUsuario;
        $pipeline = $this->pipeline;
        if($statusUsuario && $activoUsuario){
            $TipodeBusqueda = [
                '$match'=>[
                    'status'=>['$in'=>$statusUsuario],
                    'activo'=>['$in'=>$activoUsuario]
                ]
            ];
            array_push($pipeline,$TipodeBusqueda);            
        }

        $usuarios = User::raw(function($coleccion) use($pipeline){
            return $coleccion->aggregate($pipeline);
        });
        foreach ($usuarios as $usuario) {
            $seleccionados[]=$usuario->_id;
        }
        return $seleccionados;
    }

    public function obtener($pagina,$busquedaCampo){
        $informacion = [];
        $informacion['to']=0;
        $informacion['per_page']=0;
        $i = 0;
        $statusUsuario = $this->statusUsuario;
        $activoUsuario = $this->activoUsuario;
        $pipeline = $this->pipeline;
        $pipelineTotal = $this->pipelineTotal;
        $limite = 10;
        $offset = ($pagina - 1) * $limite; 
        if($statusUsuario && $activoUsuario){
            $TipodeBusqueda = [
                '$match'=>[
                    'status'=>['$in'=>$statusUsuario],
                    'activo'=>['$in'=>$activoUsuario]
                ]
            ];
            array_push($pipeline,$TipodeBusqueda);
            array_push($pipelineTotal,$TipodeBusqueda);
        }

        if($busquedaCampo){
            $CampoBusqueda = [
                '$match'=>[
                    'nombreMedico'=>new Regex($busquedaCampo,'i')
                ]
            ];
            array_push($pipeline,$CampoBusqueda);
            array_push($pipelineTotal,$CampoBusqueda);
        }



        $totalRegistros =  User::raw(function($coleccion) use($pipelineTotal){
            return $coleccion->aggregate($pipelineTotal);
        })->count();
                
        array_push($pipeline,['$skip'=>$offset],['$limit'=>$limite]);

        $usuarios = User::raw(function($coleccion) use($pipeline){
            return $coleccion->aggregate($pipeline);
        });
        Log::info("Que traes en usuarios personal");
        Log::info($usuarios);
        $informacion['total']=(int)number_format($totalRegistros);
        $informacion['last_page']=ceil ($totalRegistros / $limite);
        $last_page = ceil($totalRegistros / $limite);

        foreach ($usuarios as $usuario) {
            $informacion['data'][]=[
                "index"=>$pagina * $limite - $limite+1 + $i,
                "uid"=>$usuario->_id,
                "email"=> $usuario->email,
                "rol" => $usuario->rol,
                "status" => $usuario->status,
                "activo" => $usuario->activo,
                "created_at" => $usuario->created_at,
                "updated_at" => $usuario->updated_at,
                "no_cuenta" => $usuario->no_cuenta,
                "nombre" => strtoupper($usuario->nombre),
                "primer_apellido" => strtoupper($usuario->primer_apellido),
                "segundo_apellido" => strtoupper($usuario->segundo_apellido),
                "fecha_inicio_promocion" => $usuario->fecha_inicio_promocion,
                "fecha_termino_promocion" => $usuario->fecha_termino_promocion,
                "fecha_solicitud_acceso" => $usuario->fecha_solicitud_acceso,
                "fecha_acceso_inicio" => $usuario->fecha_acceso_inicio,
                "fecha_acceso_vencimiento" => $usuario->fecha_acceso_vencimiento,
                "dependencia" => $usuario->dependencia,
                "observaciones" => $usuario->observaciones,
                "licenciatura" => $usuario->licenciatura,
            ];
            $i++;
        }
        if($usuarios){
            $to = $pagina < $last_page ? sizeof($informacion['data']) * $pagina: $informacion['total'];
            Log::info("Que traes en el To");
            Log::info($to);
            $informacion['to']=(int)number_format($to);
            $informacion['per_page']=$limite;
            $informacion['pagina']=$pagina;
        }

        

        return $informacion;

    }

    public function obtenerDatosdelUsuario($usuario){
        $pipeline = $this->pipeline;
        
        array_push($pipeline,['$match'=>['_id'=>new ObjectId($usuario)]]);
        
        return $usuarios = User::raw(function($coleccion) use($pipeline){
            return $coleccion->aggregate($pipeline);
        })->first();
    }


    /**
     * Actualizar registro de usuario en User
     */
    public function updateDatosUsuario($usuario,$datos){
       return  User::where('_id',new ObjectId($usuario))->update($datos);
    }
    public function updateDatosPerfilUsuario($usuario,$datos){
       return  UsuariosPerfiles::where('usuarioId',new ObjectId($usuario))->update($datos);
    }


    public function AutorizarAccesoMedicoPasante ($medicos){
        $datos = [
            'status'=>3,
            'activo'=>1
        ];

        $medicosPasantes = array_map(function($medico) {
            return new ObjectId($medico);
        }, $medicos);
        return  User::whereIn('_id',$medicosPasantes)->update($datos);
    }

    public function SuspenderAccesoSistema($medicos){
        $datos = [
            'status'=>0,
            'activo'=>0
        ];

        $medicosPasantes = array_map(function($medico) {
            Log::info("Que traes en medico");
            Log::info($medico);
            return new ObjectId($medico);
        }, $medicos);

        return  User::whereIn('_id',$medicosPasantes)->update($datos);
    }
}