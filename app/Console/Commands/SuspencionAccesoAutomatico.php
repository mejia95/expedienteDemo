<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Log;
use DB;
use MongoDB\BSON\ObjectId;
class SuspencionAccesoAutomatico extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:suspencion-acceso-automatico';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hoy = date('Y-m-d');
        Log::info("Que traes en hoy");
        Log::info($hoy);
        $pipeline = [
            [
                '$project'=>
                [
                    '_id'=>1,
                    'fecha_vencimiento'=>[
                        '$dateToString'=>[
                                'format'=>"%Y-%m-%d",
                                'date'=>[
                                    '$toDate'=>'$fecha_acceso_vencimiento'
                                ],
                                'timezone'=> 'America/Mexico_City'
                        ]
                    ],
                    'rol'=>1,
                    'fecha_acceso_vencimiento'=>1
                ]
            ],
            [
                '$match'=>[
                    'rol'=>3,
                    'fecha_vencimiento'=>['$lte'=>$hoy]
                ]
            ]

        ];

        $usuarios_disponibles_suspension = User::raw(function($coleccion)use($pipeline){
            return $coleccion->aggregate($pipeline);
        })->pluck('_id');
        if($usuarios_disponibles_suspension->count()>0){
            $usuariosIds = array_map(function($id){
                return new ObjectId($id);
            },$usuarios_disponibles_suspension->toArray());
            $suspender_Acceso_usuarios = User::whereIn('_id',$usuariosIds)->update(['status'=>0,'activo'=>0]);
            Log::info("Se ha suspendido el acceso a $suspender_Acceso_usuarios usuarios");
        }
        Log::info("------------------------");
        Log::info("Fin-SuspensionAccesoAuto");

    }
}
