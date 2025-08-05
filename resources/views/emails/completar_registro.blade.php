<!DOCTYPE html>
<html>
<head>
    <title>Completar Registro - Expediente MPSS</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #1976D2;">Completar Registro en Expediente MPSS</h2>
        
        <p>Estimado(a)</p>
        
        <p>Su solicitud de acceso al Sistema de Expediente MPSS ha sido aprobada. Para completar su registro y acceder al sistema, por favor haga clic en el siguiente enlace:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('completar.registro', ['id' => $enlace->_id]) }}" 
               style="background-color: #1976D2; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; display: inline-block;">
                Completar Registro
            </a>
        </div>
        
        
        <p><strong>Importante:</strong> Este enlace expirará en 5 minutos por motivos de seguridad. Si no completa su registro en este tiempo, deberá solicitar un nuevo enlace.</p>
        
        <p>Si tiene alguna duda o no solicitó este registro, por favor contacte al responsable de servicio social.</p>
        
        <p style="margin-top: 30px;">Saludos cordiales,<br>
        <strong>Equipo de Expediente MPSS</strong></p>
    </div>
</body>
</html> 