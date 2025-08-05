<?php 

namespace App\Http\Repositorios;
use App\Models\MedicoPasantePreRegistros;
use App\Models\User;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Validator;
use Log;
class RegistroMedicosPasantesRepositorio 
{

    public function LogeoOffice365($correo){
        $url = env('LDAP_URL').env('TOKEN_LDAP');
        $url = "$url&correo=$correo";
        return $url;
    }

    public function CorreoResponsabledeServicioSocial(){
        $responsable = User::where('rol',2)->first();
        return $responsable ? $responsable->email : null;
    }

    public function enlaceValido($enlaceId){
        $enlaceValido = MedicoPasantePreRegistros::where('_id',new ObjectId($enlaceId))->where('status_solicitud',1)->first();
        if(!$enlaceValido){
            return false;            
        }

        $now = now();
        $hora_limite = $enlaceValido->fecha_solicitud->addSeconds($enlaceValido->tiempo_expiracion_solicitud_segundos);

        if($now > $hora_limite){
            return false;
        }

        return $enlaceValido;
    }
    public function ProcesoAutenticacionUsuarioValido($usuario){
        $enlaceValido = MedicoPasantePreRegistros::where('usuarioId',new ObjectId($usuario))->where('status_solicitud',1)->first();
        if(!$enlaceValido){
            return false;            
        }

        $now = now();
        $hora_limite = $enlaceValido->fecha_solicitud->addSeconds($enlaceValido->tiempo_expiracion_solicitud_segundos);

        if($now > $hora_limite){
            return false;
        }

        return $enlaceValido;
    }

    public function ObtenerEstadoSolicitud ($estado){
        $objEstado = [];
        switch($estado){
            case 1:
                $objEstado = 'El usuario esta disponible para validar';
                break;
            case 2:
                $objEstado = 'Su solicitud de acceso está siendo procesada ya no es necesario hacer este proceso de validación. Por favor, espere la notificación de confirmación de acceso.';
                break;
            case 3:
                $objEstado = 'El acceso al sistema para el correo ya ha sido otorgado, ya no es necesario volver a pedir acceso';
                break;
            case 4:
                $objEstado = 'El correo que has ingresado se encuentra en proceso para completar la información. Por favor, revisa tu bandeja de entrada para continuar con el registro.';
                break;
        }
        return $objEstado;
    }
}