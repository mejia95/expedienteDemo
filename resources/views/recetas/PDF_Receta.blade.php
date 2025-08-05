<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Receta Médica</title>

</head>

<body>
    <header>
        <div style=" margin-right:.5cm; text-align:start;">
            <img align="left" src="{{public_path('logos_consultorios/escudo.png')}}" height="80">
        </div>
        <div>
            <img align="right" src="{{public_path('logos_consultorios/medicina_simbolo.png')}}" height="80">
        </div>
        <div style="margin-left:.5cm; margin-right:.5cm; text-align:center;">
            <label class="texto_titulo" style=" display: block; margin-bottom: .2cm;">Receta médica</label>
            <label class="texto_titulo" style=" display: block; margin-bottom: .1cm;">{{ $datos_medico->nombre }}
                {{ $datos_medico->primer_apellido }} {{ $datos_medico->segundo_apellido }}</label>
            <label class="texto_titulo"
                style=" display: block; margin-bottom: .2cm;"> No.Cuenta:
                {{ $datos_medico->no_cuenta }}</label>
        </div>
        <table style="font-family: Sans-serif; font-size: 10pt; width: 90%; margin-left: 1cm; margin-right: 1cm;margin-top: .5cm; font-weight: bold;">
            <tr>                
                <td style="text-align: right;">
                    Folio: {{ $folio }}<br>
                    Fecha: {{ $fecha_hoy }}
                </td>
            </tr>
        </table>         
    </header>
    <br><br>
    <main>
        <div>
            <label class="texto_titulo" style=" margin-right:.1cm; ">Nombre del paciente:</label>
            <label><u class="texto_campos" style=" text-decoration: none">{{ $datos_paciente['primer_apellido'] }} {{ $datos_paciente['segundo_apellido'] }}
                    {{ $datos_paciente['nombre'] }}</u></label>
            <label class="texto_titulo" style="margin-left: .6cm; ">CURP: </label>&nbsp;
            <label><u class="texto_campos" style="text-decoration: none">{{ $datos_paciente['CURP'] }}</u></label>&nbsp; &nbsp;&nbsp; &nbsp;
            <br><br>
            <label class="texto_titulo" style=" margin-right:.1cm; ">Edad:</label>
            <label><u class="texto_campos" style="text-decoration: none">{{ $edad['edad'] }}</u></label>
            <label class="texto_titulo" style=" margin-left: .6cm; ">Peso:</label>&nbsp;
            <label><u class="texto_campos" style="text-decoration: none">{{ $exploracion['peso'] }}
                    {{ $exploracion['peso_unidad'] }}</u></label>
            <label class="texto_titulo" style=" margin-left: .6cm; ">Altura:</label>&nbsp;
            <label><u class="texto_campos" style="text-decoration: none">{{ $exploracion['altura'] }}
                    {{ $exploracion['altura_unidad'] }}</u></label>
            <label class="texto_titulo" style=" margin-left: .6cm;  ">Temperatura:</label>&nbsp;
            <label><u class="texto_campos" style="text-decoration: none">{{ $exploracion['temperatura']}} °</u></label>
            <br><br>
            @if (!empty($antecedentes['alergias']))
                <label class="texto_titulo" style="">Alergias: &nbsp;</label>
                <label><u class="texto_campos" style="text-decoration: none">{{ $antecedentes ? $antecedentes['alergias'] : '' }}</u></label>
            @endif
        </div>        
        @if (!empty($edad['indicaciones_receta']))
            <div >
                <label class="texto_titulo" style="">Indicaciones del médico: </label>
                <p class="texto_campos"> {{ $edad['indicaciones_receta'] }}</p>
            </div>
        @endif  
        @if (!empty($plan_terapeutico))
            <div>
                <label class="texto_titulo" style="">Plan terapéutico:</label>
                <p class="texto_campos">{{ $plan_terapeutico }}</p>
            </div>
        @endif    
        @if (!empty($ordenes_estudios))
            <div>
                <label class="texto_titulo" style="">Órdenes de estudios:</label>
                <p class="texto_campos"> {{ $ordenes_estudios }}</p>
            </div>
        @endif  
        @if (count($datos_tratamiento) > 0)
            <div>
                <label class="texto_titulo" style="">Medicamentos recetados:</label><br><br>
                @foreach ($datos_tratamiento as $index => $tratamiento)
                    <label class="texto_campos">
                        {{ $index + 1 }}.-&nbsp; &nbsp;
                        {{ $tratamiento['id'] }}&nbsp; &nbsp;
                        {{ $tratamiento['dosis'] }}
                    </label><br><br>
                @endforeach
            </div>
        @endif
        <div style="margin-left:10cm; ">
            <label class="firma texto_titulo"> Firma del médico pasante: <u
                    style="margin-left:2cm; text-decoration: none "></u></label>
        </div>        
        <div>
            <label class="info_cuenta texto_titulo" >Médico en formación: {{ $medico_formacion['nombre'] }}
                {{ $medico_formacion['primer_apellido'] }} {{ $medico_formacion['segundo_apellido'] }} </label>
            <label class="info texto_titulo" >No.Cuenta: {{ $medico_formacion['no_cuenta'] }} </label>
            
        </div>           
        <footer>     
            <div class="direccion">
                <label style="margin-right:.1cm; " class="texto_titulo">Consultorio:</label>
                <label><u style="text-decoration: none" class="texto_campos">{{ $datos_consultorio['nombre']}}</u></label><br>
                <label style="margin-right:.1cm;" class="texto_titulo">Ubicación física:</label>
                <label><u style="text-decoration: none" class="texto_campos"> {{ $datos_consultorio['direccion'] }}</u></label>
            </div>
            <div class="direccion"style="margin-left:17cm; text-align:end;">
                 <img src="data:image/svg+xml;base64, {!! base64_encode($qrCode) !!}" alt="Código QR">
            </div>                            
        </footer>
    </main>
</body>

</html>
<style>
    .texto_titulo {
        font-family: Sans-serif;
        font-size: 10pt;
        font-weight: bold;
    }
    .texto_campos {
        font-family: Sans-serif;
        font-size: 10pt;        
    }
    html {
        min-height: 100%;
        position: relative;
    }

    body {
        margin: 0;
        margin-bottom: 40px;
    }

    .firma {
        position: absolute;
        bottom: 160;
        text-align: right;
    }

    .info {
        position: absolute;
        bottom: 100;
        text-align: right;
    }

    .info_cuenta {
        position: absolute;
        bottom: 120;
        text-align: right;
    }

    .direccion {
        position: absolute;
    }


    footer {
        bottom: 20;
        position: absolute;
        left: 0;
        right: -7%
            /* padding-right:-30% */

    }

    .cuadrado {
        width: 150px;
        /* Ancho de 150 píxeles */
        height: 150px;
        /* Alto de 150 píxeles */
        background: red;
        /* Fondo de color rojo */
        border: 1px solid #000;
        /* Borde color negro y de 1 píxel de grosor */
    }
</style>
