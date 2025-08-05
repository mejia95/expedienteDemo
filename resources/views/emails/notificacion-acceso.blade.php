<!DOCTYPE html>
<html>
<head>
    <title>Acceso Autorizado - Expediente MPSS</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h2 style="color: #1976D2;">¡Bienvenido al Sistema de Expediente MPSS!</h2>
        
        <p>Estimado(a) {{ $usuario->nombre }} {{ $usuario->primer_apellido }} {{ $usuario->segundo_apellido }},</p>
        
        <p>Nos complace informarte que tu acceso al Sistema de Expediente MPSS ha sido autorizado. A partir de este momento, podrás acceder al sistema utilizando tu correo electrónico institucional.</p>
        
        <div style="background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin: 20px 0;">
            <p style="margin: 0;"><strong>Información de acceso:</strong></p>
            <ul style="margin: 10px 0;">
                <li>Correo electrónico: {{ $usuario->email }}</li>
                <li>Número de cuenta: {{ $usuario->no_cuenta }}</li>
                <li>Código para autorización de recetas: <b> {{ $codigo }} </b></li>
            </ul>
        </div>

        <p>Para acceder al sistema, por favor visita: <a href="{{ url('/login') }}" style="color: #1976D2;">{{ url('/login') }}</a></p>
        <p><b>Recuerda tener a la mano el código para la autorización de recetas, el cual es necesario para realizar cualquier trámite relacionado con la prescripción de medicamentos.</b></p>
        <p>Si tienes alguna duda o necesitas asistencia, no dudes en contactar al responsable de servicio social.</p>
        
        <p style="margin-top: 30px;">Saludos cordiales,<br>
        Equipo de Expediente MPSS</p>
    </div>
</body>
</html> 