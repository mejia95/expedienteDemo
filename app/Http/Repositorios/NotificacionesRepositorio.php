<?php 

namespace App\Http\Repositorios;
use App\Models\User;
use Log;
use MongoDB\BSON\ObjectId;
use Carbon\Carbon;
use Auth;
class NotificacionesRepositorio 
{

    public $pipelineAccesoMedicoPasante = [
        
        [
            '$project' => [
                '_id' => 1,
                'status' => 1,
                'activo' => 1
            ]
        ]
    ];

    
    public function AutorizarAccesoMedicoPasante ($medicos){
        $pipeline = $this->pipelineAccesoMedicoPasante;
        $medicosObjectIds = array_map(function($id) {
            return new ObjectId($id);
        }, $medicos);

        // Add lookup to get UsuariosPerfiles
        array_push($pipeline, [
            '$lookup' => [
                'from' => 'UsuariosPerfiles',
                'localField' => '_id',
                'foreignField' => 'usuarioId',
                'as' => 'Perfil'
            ]
        ]);

        // Unwind the Perfil array
        array_push($pipeline, [
            '$unwind' => '$Perfil'
        ]);

        // Match users with non-null consultorio
        array_push($pipeline, [
            '$match' => [
                '_id' => ['$in' => $medicosObjectIds],
                'Perfil.consultorio' => ['$ne' => null]
            ]
        ]);

        // Get users with non-null consultorio
        $usuariosConConsultorio = User::raw(function($coleccion) use($pipeline) {
            return $coleccion->aggregate($pipeline);
        });

        // Get IDs of users with consultorio
        $idsConConsultorio = [];
        foreach ($usuariosConConsultorio as $usuario) {
            $idsConConsultorio[] = $usuario->_id;
        }

        $hoy = now();
        $hoyClear = now();
        $vencimiento_acceso = $hoy->addMonths(12);
        $usuarios = User::whereIn('_id', $idsConConsultorio)->update([
            'status' => 3,
            'activo' => 1,
            'notificado' => 0,
            'fecha_acceso_inicio' => $hoyClear,
            'fecha_acceso_vencimiento' => $vencimiento_acceso,
            'updated_by'=>new ObjectId(Auth::user()->_id),
        ]);
        
        Log::info("Se autorizaron el acceso a ".$usuarios." usuarios");
        return $usuarios;
    }
}