<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UsuariosPerfiles;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Log;
use DB;
class NotificacionAccesoMedicoPasante extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notificacion:acceso-medico-pasante';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía notificaciones por correo a médicos pasantes con acceso autorizado';

    private $pipeline = [
        [
            '$match' => [
                'status' => 3,
                'activo' => 1,
                'notificado' => 0
            ]
        ],
        [
            '$lookup' => [
                'from' => 'UsuariosPerfiles',
                'localField' => '_id',
                'foreignField' => 'usuarioId',
                'as' => 'perfil'
            ]
        ],
        [
            '$unwind' => '$perfil'
        ],
        [
            '$project' => [
                '_id' => 1,
                'email' => 1,
                'nombre' => '$perfil.nombre',
                'primer_apellido' => '$perfil.primer_apellido',
                'segundo_apellido' => '$perfil.segundo_apellido',
                'no_cuenta' => '$perfil.no_cuenta'
            ]
        ],
        [
            '$limit'=>50
        ]
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $pipeline = $this->pipeline;
            $usuarios = User::raw(function($coleccion) use($pipeline) {
                return $coleccion->aggregate($pipeline);
            });

            foreach ($usuarios as $usuario) {
                try {

                    //Log::info("Que usuarios traes");
                    //Log::info($usuario['no_cuenta']);
                    $codigo = (int)str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
                    $update = UsuariosPerfiles::where('no_cuenta',$usuario['no_cuenta'])->update(['codigo'=> $codigo]);                    
                   
                    Mail::send('emails.notificacion-acceso', [
                        'codigo' => $codigo,
                        'usuario' => $usuario
                    ], function($message) use ($usuario) {
                        $message->to($usuario->email)
                               ->subject('Acceso Autorizado - Expediente MPSS');
                    });

                    //Log::info("Se ha notificado al usuario: " . $usuario->email);
                    
                    // Marcar como notificado
                    User::where('_id', $usuario->_id)->update(['notificado' => 1]);
                    
                } catch(\Throwable $e) {
                    Log::error("Error al notificar al usuario " . $usuario->email . ": " . $e->getMessage());
                    continue; // Continue with next user even if this one fails
                }
            }
            
            $this->info('Proceso de notificación completado.');
        } catch (\Exception $e) {
            Log::error("Error en notificación de acceso: " . $e->getMessage());
            $this->error('Error al enviar notificaciones: ' . $e->getMessage());
        }
    }
}
