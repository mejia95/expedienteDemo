<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Notificación de Solicitudes Pendientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: #1976D2;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            padding: 20px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            background: #1976D2;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Notificación de Solicitudes Pendientes</h2>
        </div>
        <div class="content">
            <p>Estimado(a) Responsable de Servicio Social,</p>
            
            <p>Se le informa que tiene solicitudes de servicio social pendientes de autorización en el sistema Expediente MPSS.</p>

            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0;">
                <p style="margin: 0;"><strong>Datos del estudiante:</strong></p>
                <p style="margin: 10px 0 0 0;">
                    <strong>Nombre completo:</strong> {{ $nombre }} {{ $primer_apellido }} {{ $segundo_apellido }}<br>
                    <strong>Correo electrónico:</strong> {{ $correo }}
                </p>
            </div>

            @if(isset($observaciones) && !empty($observaciones))
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0;">
                <p style="margin: 0;"><strong>Observación del estudiante:</strong></p>
                <p style="margin: 10px 0 0 0; white-space: pre-line;">{{ $observaciones }}</p>
            </div>
            @endif

            <p>Por favor, acceda al sistema para revisar y autorizar estas solicitudes.</p>

            <a href="{{ route('personal-medicospasantes') }}" class="button">Revisar Solicitudes</a>

            <p>Si tiene alguna duda o necesita asistencia, no dude en contactarnos.</p>

            <p>Saludos cordiales,<br>
            Sistema Expediente MPSS</p>
        </div>
        <div class="footer">
            <p>Este es un correo automático, por favor no responda a este mensaje.</p>
        </div>
    </div>
</body>
</html> 