<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CatTipoSangre;
use App\Models\CatGenero;
use App\Models\CatEstadoCivil;
use App\Models\CatNacionalidad;
use App\Models\CatDiagnosticos;
use App\Models\Toxicomanias;
use App\Models\CatEnfermedadesComunes;
use App\Models\CatVisualAgudezas;
use App\Models\CatMedicamentos;
use App\Models\CatMedicamentosConsultorio;
use App\Models\UsuariosPerfiles;
use Illuminate\Support\Facades\Validator;
use MongoDB\BSON\Regex;
use MongoDB\BSON\ObjectId;
use Auth;
use Carbon\Carbon;
use Session;

class CatalogosController extends Controller
{
    public function Catalogos(Request $request)
    {        
        $tipoSangre = CatTipoSangre::get();
        $CatGenero = CatGenero::get();
        $CatEstadoCivil = CatEstadoCivil::get();
        $CatNacionalidad = CatNacionalidad::get();
        
        return response()->json([   
            'tipoSangre' => $tipoSangre,
            'CatGenero' => $CatGenero,
            'CatEstadoCivil' => $CatEstadoCivil,
            'CatNacionalidad' => $CatNacionalidad],200);
    //     return Inertia::render('Pacientes/NuevoPaciente', [
    //         'tipoSangre' => $tipoSangre,
    //         'CatGenero' => $CatGenero,
    //         'CatEstadoCivil' => $CatEstadoCivil,
    //         'CatNacionalidad' => $CatNacionalidad
    //     ]);     
    // }
    }

    public function ConsultaToxicomanias()
    {
        return $toxicomanias = Toxicomanias::where('estado',1)->get();
    }

    public function enfermedadesFrecuentes()
    {
     return $enfermedades = CatEnfermedadesComunes::get();
    }

   public function GuardarToxicomania(Request $request)
    {
        $toxicomania = $request->toxicomania;

        $validator = Validator::make($request->all(), [
            'toxicomania' => 'bail|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('toxicomania')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Toxicomanías. </strong>(Números, emojis, etc)</p>";
            }
            return response()->json(array('message'=>$message),422);
        }

        $ultimo = Toxicomanias::orderBy('_id','desc')->first();

        $valor =  $ultimo->valor + 1;
        $nuevaEnfermedad = Toxicomanias::insert(['etiqueta'=>$toxicomania,'valor'=>$valor,'estado'=>1]);
        $ultimo = Toxicomanias::orderBy('_id','desc')->first(); 

        return response()->json(['msg'=>'ok','nueva_toxicomania'=>$ultimo->valor],200);
             
    }

    public function agregarEnfermedadFrecuente(Request $request){
        
        $enfermedad = $request->enfermedad;

        $validator = Validator::make($request->all(), [
            'enfermedad' => 'bail|regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            if ($errors->has('enfermedad')) {
                $message="<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Antecedentes personales patológicos. </strong>(Números, emojis, etc)</p>";
            }
            return response()->json(array('message'=>$message),422);
        }

        $ultimo = CatEnfermedadesComunes::orderBy('_id','desc')->first();

        $eID =  $ultimo->eID + 1;
        $nuevaEnfermedad = CatEnfermedadesComunes::insert(['eNombre'=>$enfermedad,'eID'=>$eID,'eEstado'=>true]);
        $ultimo = CatEnfermedadesComunes::orderBy('_id','desc')->first();
        
        return response()->json(['msg'=>'ok','nueva_enfermedad'=>$ultimo->eID]);
    }
    
    public function AgudezasVisuales()
    {        
        $agudezas = CatVisualAgudezas::where('aEstado',1)->get();

        return $agudezas;
    }

    public function DiagnosticosTodos(Request $request)
    {
      
        $busqueda = $request->busqueda;
        $seleccionados = $request->seleccionados ? array_map(function($valor){
            return intval($valor);
        },$request->seleccionados) : [];        
        $diagnosticos_clear = [];
       $pipeline = [
            [
                '$project'=>['dId'=>1,'diNombre'=>1,'dClasificacion'=>1]
            ],
            [
                '$addFields'=>[
                    'NombreDiagnostico'=>[
                        '$toString'=>[
                              '$concat' => ['$dClasificacion',' - ',['$ifNull'=>['$diNombre',' ']]]
                        ]
                    ]
                ]
            ],[
                '$match'=>[
                    'NombreDiagnostico' => new Regex($busqueda,'i')
                ]
            ],
            [
                '$limit'=>10
            ]
        ];
        $pipelineSeleccionados = [
            [
                '$project'=>['dId'=>1,'diNombre'=>1,'dClasificacion'=>1]
            ],
            [
                '$addFields'=>[
                    'NombreDiagnostico'=>[
                        '$toString'=>[
                              '$concat' => ['$dClasificacion',' - ',['$ifNull'=>['$diNombre',' ']]]
                        ]
                    ]
                ]
            ],[
                '$match'=>[
                    'dId' => [
                        '$in'=>$seleccionados
                    ]
                ]
            ],
        ];




        $diagnosticos =  CatDiagnosticos::raw(function($collection)use($pipeline){
            return $collection->aggregate($pipeline);
        })->toArray();
        $diagnosticosSeleccionados =  CatDiagnosticos::raw(function($collection)use($pipelineSeleccionados){
            return $collection->aggregate($pipelineSeleccionados);
        })->toArray();
        $diagnosticos_clear = [];
        $ids_agregados = [];


         $diagnosticosTodos = array_merge($diagnosticos,$diagnosticosSeleccionados);
        foreach ($diagnosticosTodos as $diagnostico ) {
            // $diagnosticos_clear [] = [
            //     'dId'=>$diagnostico['dId'],
            //     'diNombre'=>$diagnostico['dClasificacion']." - ".$diagnostico['diNombre']
            // ];
            if (!in_array($diagnostico['dId'], $ids_agregados)) {
                $diagnosticos_clear[] = [
                    'dId' => $diagnostico['dId'],
                    'diNombre' => $diagnostico['dClasificacion'] . " - " . $diagnostico['diNombre']
                ];
                $ids_agregados[] = $diagnostico['dId'];
            }
        }
        return response()->json($diagnosticos_clear);
    }

    public function ConsultaMedicamentos(Request $request)
    {        
        if(Session::get('RolUsuario')!=4){abort(510);}  
        try{ 
            $i=0;
            $rol = Auth::user()->rol;
            $userId = Auth::user()->id;
            $consultorio = UsuariosPerfiles::where('usuarioId',new ObjectId($userId))->first();
            $ordenarPor = $request->ordenarPor ? $request->ordenarPor : 'numero';
            $orden = $request->orden ? $request->orden : 1; 
            $pagina = $request->pagina ?? 1;    
            $medicamentos_seleccionados = [];
            $limite = 10;
            $offset = ($pagina - 1) * $limite; 
            $condiciones = array();

            $datos_pacientes = [];
            $busqueda = $request->busqueda ? $request->busqueda :null;

            $medicamentos_seleccionados = CatMedicamentosConsultorio::where('consultorio_id',new ObjectId($consultorio->consultorio))->first();
            
            $pipeline = [
                    [
                        '$project' => [
                            'numero' => 1,
                            'farmacos' => 1,
                            'forma_farmaceutica' => 1,
                            'concentracion' => 1,
                            'denominacion_distintiva' => 1,
                            'es_visible' => 1,
                            'estado' => 1,
                            'registro_sanitario' => 1,
                            'titular' => 1,
                            'indicacion_terapeutica' => 1
                        ]
                    ],
                    [
                        '$addFields' => [
                            'numero_padre' => '$numero'
                        ]
                    ],
                    [
                        '$lookup' => [
                            'from' => 'CatMedicamentosConsultorio',
                            'localField' => 'numero',
                            'foreignField' => 'numero',
                            'as' => 'medicamentos_consultorio',
                            'pipeline' => [
                                [
                                    '$match' => [
                                        'consultorio_id' => new ObjectId($consultorio->consultorio)
                                    ]
                                ],
                                [
                                    '$project' => [
                                        'numero' => 1
                                    ]
                                ]
                            ]
                        ]
                    ],
                    [
                        '$addFields' => [
                            'visible' => [
                                '$cond' => [
                                    'if' => [ '$gt' => [ [ '$size' => '$medicamentos_consultorio' ], 0 ] ],
                                    'then' => 'Visible',
                                    'else' => 'No disponible'
                                ]
                            ]
                        ]
                    ]
                ];


            $pipelineTotal =  $pipeline;


            if($busqueda){
                $regex = new Regex($busqueda, 'i');
                $condicion = [
                    '$or' => [
                        ['farmacos' => $regex],
                        ['forma_farmaceutica' => $regex],
                        ['denominacion_distintiva' => $regex],
                        ['visible' => $regex],
                        ['concentracion' => $regex],
                    ]
                ];
                $condiciones['$match']['$and'][] = $condicion;
            }

            if($condiciones){
                $pipeline[] = $condiciones;
                $pipelineTotal[] = $condiciones;
                
            }

            $pipelineTotal[] = [
                '$count' => 'total_medicamentos'
            ];

            $total_medicamentos = CatMedicamentos::raw(function($table) use($pipelineTotal){
                return $table->aggregate($pipelineTotal);
            })->first();
                
            $TotalMedicamentos = $total_medicamentos ? $total_medicamentos->total_medicamentos : 0;

           // array_push($pipeline,['$sort'=>[$ordenarPor=>(int)$orden]]);
            array_push($pipeline,['$skip'=>$offset],['$limit'=>$limite]);

            $medicamentos = CatMedicamentos::raw(function($table) use($pipeline){
                return $table->aggregate($pipeline);
            });

            if(sizeof($medicamentos)<1){
                $datos_medicamentos['data'] = [];
            }
           
            $datos_medicamentos['total']=intval($TotalMedicamentos);
            $datos_medicamentos['last_page']=ceil ($TotalMedicamentos / $limite);
            $last_page = ceil ($TotalMedicamentos / $limite);

            foreach ($medicamentos as $medicamento) {
                $datos_medicamentos['data'][]=[
                    "numero"=>$medicamento['numero'],
                    'visible'=>$medicamento['visible'],
                    "farmacos"=>$medicamento['farmacos'],
                    "forma_farmaceutica"=>$medicamento['forma_farmaceutica'],
                    "concentracion"=>$medicamento['concentracion'],
                    "denominacion_distintiva"=>$medicamento['denominacion_distintiva'],
                    "estado"=>$medicamento['estado'],
                    "consultas"=>$total_medicamentos,
                    "registro_sanitario"=>$medicamento['registro_sanitario'],
                    "titular"=>$medicamento['titular'],
                    "indicacion_terapeutica"=>$medicamento['indicacion_terapeutica'],
                ];
                $i++;
            }

            $to = $pagina < $last_page ? sizeof($datos_medicamentos['data']) * $pagina : $datos_medicamentos['total'];
            $datos_medicamentos['to']=intval($to);
            $datos_medicamentos['per_page']=$limite;
            $datos_medicamentos['pagina']=$pagina;
            $datos_medicamentos['medicamentos_seleccionados']=$medicamentos_seleccionados ? $medicamentos_seleccionados->numero : [];

            return $datos_medicamentos;
        }catch(\Throwable $e){
            return $e;
           return response()->json(['message'=>'Ha ocurrido un error al obtener la información de los pacientes.'.$e],500);
        } 
        
    }

    public function GuardarMedicamentosConsultorio(Request $request)
    {               
        $medicamentos = $request->medicamentos;
        $userId = Auth::user()->id;

        $consultorio = UsuariosPerfiles::where('usuarioId',new ObjectId($userId))->first();
        $hoy = Carbon::now(); // Fecha y hora actual
        $soloFecha = Carbon::now()->toDateString();
    
        //$medicamento['usuario_id'] = $userId;
        $consultorio_existe = CatMedicamentosConsultorio::where('consultorio_id',new ObjectId($consultorio->consultorio))->first();    

            if(!$consultorio_existe){
                $medicamentos = [
                    "numero"=>$medicamentos,            
                    "consultorio_id"=>new ObjectId($consultorio->consultorio),
                    "created_usuarioId"=>new ObjectId($userId),
                ];
                $DatosGuardados = CatMedicamentosConsultorio::create($medicamentos);
            }else{
                $consultorio_existe->numero = $medicamentos;
                $consultorio_existe->{'updated_usuarioId_' . $soloFecha} = new ObjectId($userId);
                $consultorio_existe->{'updated_date_' . $soloFecha} = $hoy;                
                $consultorio_existe->save();
            }

        return response()->json(['mensaje'=>'Medicamentos guardados correctamente.'],200);
    }

    public function ConsultaMedicamentosTodos(){         
        return CatMedicamentos::pluck('numero');   
    }

    public function ConsultaMedicamentosConsultorio(){  
                
        $userId = Auth::user()->id;

        $consultorio = UsuariosPerfiles::where('usuarioId',new ObjectId($userId))->first();              

        $medicamentos_seleccionados = CatMedicamentosConsultorio::raw(function ($collection) use ($consultorio) {
            return $collection->aggregate([
               [
                    '$lookup' => [
                        'from' => 'CatMedicamentos',
                        'localField' => 'numero',
                        'foreignField' => 'numero',
                        'as' => 'medicamentos',
                    ],
                ],
                [
                    '$match' => [                        
                        'consultorio_id' => new ObjectId($consultorio->consultorio),
                    ],
                ],
                [
                    '$project'=>[
                        'medicamentos.numero'=>1, 
                        'medicamentos.farmacos'=>1,
                        'medicamentos.forma_farmaceutica'=>1,                   
                    ]
                ],   

            ]);
        });
        //$medicamentos_seleccionados = CatMedicamentosConsultorio::where('consultorio_id',new ObjectId($consultorio->consultorio))->first(); 
        return $medicamentos_seleccionados;   
    }
}
