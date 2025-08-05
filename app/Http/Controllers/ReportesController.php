<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use MongoDB\BSON\ObjectId;
use App\Http\Repositorios\ReportesRepositorio;
use App\Http\Repositorios\PacientesRepositorio;
class ReportesController extends Controller
{

    private $EPICLAVE_SUIVE1 = [
        '44',
        '85',
        '86',
        '82',
        '83',
        '87',
        '32',
        '42',
        '38',
        '100',
        '37',
        '137',
        '99',
        '75',
        '40',
        '101',
        '176',
        '90',
        '33',
        '01',
        '06',
        '05',
        '08',
        '09',
        '02',
        '07',
        '93',
        '04',
        '10',
        '14',
        '03',
        '177',
        '178',
        '19',
        '18',
        '15',
        '16',
        '17',
        '191',
        '92',
        '25',
        '23',
        '24',
        '21',
        '26',
        '22',
        '20',
        '179',
        '192',
        '27',
        '189',
        '89',
        '76',
        '28',
        '88',
        '77',
        '81',
        '504',
        '78',
        '175',
        '80',
        '146',
        '180',
        '183',
        '188',
        '29',
        '102',
        '218',
        '219',
        '30',
        '12',
        '103',
        '34',
        '35',
        '45',
        '73',
        '104',
        '39',
        '173',
        '43',
        '36',
        '72',
        '68',
        '105',
        '186',
        '187',
        '181',
        '182',
        '194',
        '144',
        '145',
        '69',
        '74',
        '235',
        '64',
        '66',
        '98',
        '41',
        '110',
        '48',
        '127',
        '49',
        '136',
        '112',
        '46',
        '47',
        '51',
        '52',
        '54',
        '128',
        '109',
        '111',
        '57',
        '220',
        '221',
        '222',
        '223',
        '224',
        '225',
        '226',
        '94',
        '107',
        '91',
        '151',
        '152',
        '106',
        '153',
        '125',
        '150',
        '148',
        '234',
        '114',
        '115',
        '116',
        '135',
        '155',
        '119',
        '97',
        '117',
        '118',
        '203',
        '204',
        '205',
        '206',
        '207',
        '208',
        '209',
        '210',
        '211',
        '212',
        '213',
        '214',
        '215',
        '216',
        '217',
        '129',
        '130',
        '131',
        '184',
        '96',
        '169',
        '170',
        '171',
        '195',
        '196',
        '197',
        '198',
        '199',
        '200',
        '201',
        '202',
        '123',
        '124',
        '126',
        '132',
        '227',
        '228',
        '229',
        '122',
        '172',
        '230',
        '231',
        '232',
        '233'
    ];

    public function __construct(ReportesRepositorio $reportes, PacientesRepositorio $pacientes)
    {
        $this->reportes = $reportes;
        $this->pacientes = $pacientes;
    }

    /**
     * UI SUIVE
     */
    public function suive()
    {
        return Inertia::render('Reportes/SUIVE',[
            'breadcrumbs'=>array(['title'=>'Reporte SUIVE','href'=>'/reportes/suive']),
            'ruta_valor'=>'reportes'
        ]);
    }
    public function MostrarUltimosPacientesCreados()
    {
        return $informacion =  $this->pacientes->UltimosPacientesCreados([]);
        
    }
    public function MostrarUltimosPacientesModificados()
    {
        return $informacion =  $this->pacientes->UltimosPacientesModificados([]);
        
    }
    public function MostrarPacientes()
    {
        $informacion =  $this->pacientes->InformacionPacientes([]);
        //$informacion =  $this->pacientes->ObtenerUltimosPacientesCreados([]);
        return Inertia::render('Reportes/Pacientes',[
            'breadcrumbs'=>array(['title'=>'Reporte de pacientes','href'=>'/reportes/pacientes']),
            'pacientes'=>$informacion,
            'ruta_valor'=>'reportes'
        ]);
        
    }
    public function pacientes(Request $request)
    {
        return $informacion =  $this->pacientes->InformacionPacientes($request);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function getSUIVE(Request $request)
    {
        //
        $tipoDiagnostico = $request->tipoDiagnostico ? $request->tipoDiagnostico : 1;
        $time = Carbon::now();
        switch ($tipoDiagnostico) {
            case 1:
                $diagnosticoColeccion = "ConsultaDiagnosticoSindromatico";
                $FileDiagnostico = "SUIVE-Sindromatico_$time";
                break;
            case 2:
                $diagnosticoColeccion = "ConsultaDiagnosticoEtiologicos";
                $diagnosticoColeccioCampon = "SUIVE-Etiologicos_$time";

                break;
            case 3:
                $diagnosticoColeccion = "ConsultaDiagnosticoDiferenciales";
                $FileDiagnostico = "SUIVE-Diferenciales_$time";
                break;

            default:
                $diagnosticoColeccion = "ConsultaDiagnosticoSindromatico";
                $FileDiagnostico = "SUIVE-Sindromatico_$time";
                break;
        }

        $comunidades =  $request->comunidades ? $request->comunidades : [];
        $fechaInicioReporte = date('Y-m-d', strtotime($request->fechaInicioReporte));
        $fechaCierreReporte = date('Y-m-d', strtotime($request->fechaCierreReporte));

        $semanaInicio = date('W', strtotime($request->fechaInicioReporte));
        $semanaCierre = date('W', strtotime($request->fechaCierreReporte));

        $primerDiaReporte = date('d', strtotime($request->fechaInicioReporte));
        $deReporte = strtotime($request->fechaInicioReporte);
        $fechaFormateada = strftime('%B del %Y', $deReporte);

        $alDiaReporte = date('d', strtotime($request->fechaCierreReporte));
        $deMesReporte = strtotime($request->fechaCierreReporte);
        $deMesReporteFormateado = strftime('%B', $deMesReporte);
        $delAnioReporte = date('y', strtotime($request->fechaCierreReporte));

        if (sizeof($comunidades) < 1 or strlen($fechaInicioReporte) < 1 or strlen($fechaCierreReporte) < 1) {
            return response()->json(['message' => 'Asegurate de haber ingresado todos los campos necesarios para generar el reporte'], 500);
        }

        $arregloIDS = array_map(function ($comunidad) {
            return new ObjectId($comunidad);
        }, $comunidades);

        $pipeline = [
            [
                '$project' => ['_id' => 1, 'id_paciente' => 1, 'created_at' => 1, 'id_consultorio' => 1, 'fecha_creacion' => [
                    '$dateToString' => [
                        'format' => "%Y-%m-%d",
                        'date' => '$created_at',
                        'timezone' => 'America/Mexico_City'
                    ]
                ],]
            ],
            [
                '$lookup' => [
                    'from' => 'PacientesPerfil',
                    'localField' => 'id_paciente',
                    'foreignField' => '_id',
                    'as' => 'Paciente'
                ]
            ],
            [
                '$lookup' => [
                    'from' => $diagnosticoColeccion,
                    'localField' => '_id',
                    'foreignField' => 'consulta',
                    'as' => 'ConsultaDiagnostico'
                ]
            ],
            [
                '$lookup' => [
                    'from' => 'ConsultaTratamiento',
                    'localField' => '_id',
                    'foreignField' => 'id_consulta',
                    'as' => 'ConsultaTratamiento'
                ]
            ],
            [
                '$unwind' => ['path' => '$Paciente', 'preserveNullAndEmptyArrays' => true]
            ],
            [
                '$unwind' => ['path' => '$ConsultaDiagnostico', 'preserveNullAndEmptyArrays' => true]
            ],
            [
                '$unwind' => ['path' => '$ConsultaTratamiento', 'preserveNullAndEmptyArrays' => true]
            ],
            [
                '$match' => [
                    'id_consultorio' => [
                        '$in' => $arregloIDS
                    ],
                    'fecha_creacion' => [
                        '$gte' => $fechaInicioReporte,
                        '$lte' => $fechaCierreReporte
                    ],
                    'ConsultaDiagnostico.clave_diagnostico' => [
                        '$in' => $this->EPICLAVE_SUIVE1
                    ]

                ]
            ],
            [
                '$addFields' => [
                    'EdadConsulta' => [
                        '$toInt' => [
                            '$arrayElemAt' => [['$split' => ['$ConsultaTratamiento.edad', ' ']], 0]
                        ]
                    ],
                    'Sexo' => '$Paciente.genero',
                    'Diagnostico' => '$ConsultaDiagnostico.clave_diagnostico'
                ]
            ],
            [
                '$project' => ['ConsultaDiagnostico' => 0, 'ConsultaTratamiento' => 0, 'Paciente' => 0]
            ],
            [
                '$group' => [
                    '_id' => '$Diagnostico',
                    'menos1AH' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$eq' => ['$EdadConsulta', 0]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'menos1AM' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$eq' => ['$EdadConsulta', 0]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango1a4H' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 1]], ['$lte' => ['$EdadConsulta', 4]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango1a4M' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 1]], ['$lte' => ['$EdadConsulta', 4]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango5a9H' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 5]], ['$lte' => ['$EdadConsulta', 9]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango5a9M' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 5]], ['$lte' => ['$EdadConsulta', 9]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango10a14H' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 10]], ['$lte' => ['$EdadConsulta', 14]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango10a14M' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 10]], ['$lte' => ['$EdadConsulta', 14]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango15a19H' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 15]], ['$lte' => ['$EdadConsulta', 19]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango15a19M' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 15]], ['$lte' => ['$EdadConsulta', 19]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango20a24H' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 20]], ['$lte' => ['$EdadConsulta', 24]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango20a24M' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 20]], ['$lte' => ['$EdadConsulta', 24]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango25a44H' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 25]], ['$lte' => ['$EdadConsulta', 44]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango25a44M' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 25]], ['$lte' => ['$EdadConsulta', 44]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango45a49H' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 45]], ['$lte' => ['$EdadConsulta', 49]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango45a49M' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 45]], ['$lte' => ['$EdadConsulta', 49]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango50a59H' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 50]], ['$lte' => ['$EdadConsulta', 59]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango50a59M' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 50]], ['$lte' => ['$EdadConsulta', 59]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango60a64H' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 60]], ['$lte' => ['$EdadConsulta', 64]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango60a64M' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 60]], ['$lte' => ['$EdadConsulta', 64]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango65masH' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 65]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Rango65masM' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$gte' => ['$EdadConsulta', 65]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'IgnH' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$eq' => ['$EdadConsulta', ""]], ['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'IgnM' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$eq' => ['$EdadConsulta', ""]], ['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'TotalH' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$eq' => ['$Sexo', 1]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'TotalM' => [
                        '$sum' => [
                            '$cond' => [
                                ['$and' => [['$eq' => ['$Sexo', 2]]]],
                                1,
                                0
                            ]
                        ]
                    ],
                    'Total' => [
                        '$sum' => 1
                    ]
                ]
            ]
        ];

        $informacion = $this->reportes->coleccion('Consultas', $pipeline)->obtenerInformacion();

        if (sizeof($informacion->informacion) < 1) {
            return response()->json(['message' => 'No se encontró información relevante para generar el reporte SUIVE. Por favor, verifique los parámetros de búsqueda y vuelva a intentarlo.'], 500);
        }


        //$reporte =$this->reportes->coleccion('Consultas',$pipeline)->obtenerInformacion()->FormatearSUIVE()->ConPlantilla(true)->GenerarReporte();
        $reporte = $informacion->FormatearSUIVE()->ConPlantilla(true)->GenerarReporte($FileDiagnostico, $primerDiaReporte, $fechaFormateada, $alDiaReporte, $deMesReporteFormateado, $delAnioReporte, $arregloIDS, $semanaInicio, $semanaCierre);

        $tempFile = storage_path("app/public/$FileDiagnostico.xlsx");
        return response()->download($tempFile)->deleteFileAfterSend(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
