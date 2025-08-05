<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstudiosRequest extends FormRequest
{
    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'otros'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_laboratorio.link_laboratorio'=>['bail','nullable','max:255','url'],
            'datos_electro.link_electro'=>['bail','nullable','max:255','url'],
            'datos_radiografia.link_radiografia'=>['bail','nullable','max:255','url'],
            'datos_laboratorio.archivoLab1'=>['bail','nullable','file','mimes:pdf,jpg,png','max:3072'],
            'datos_laboratorio.archivoLab2'=>['bail','nullable','file','mimes:pdf,jpg,png','max:3072'],
            'datos_laboratorio.archivoLab3'=>['bail','nullable','file','mimes:pdf,jpg,png','max:3072'],
            'datos_laboratorio.hematocrito'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.leucocitos'=>['bail','nullable','numeric'],
            'datos_laboratorio.linfocitos'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.monocitos'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.volumen_corpuscular'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.plaquetas'=>['bail','nullable','numeric'],
            'datos_laboratorio.glucemia'=>['bail','nullable','numeric','regex:/^\d{1,7}(\.\d{1,2})?$/'],
            'datos_laboratorio.urea'=>['bail','nullable','numeric','regex:/^\d{1,7}(\.\d{1,2})?$/'],
            'datos_laboratorio.creatinina'=>['bail','nullable','numeric','regex:/^\d{1,7}(\.\d{1,2})?$/'],
            'datos_laboratorio.sodio'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.potasio'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.cloro'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.got'=>['bail','nullable','numeric'],
            'datos_laboratorio.gpt'=>['bail','nullable','numeric'],
            'datos_laboratorio.fosfatasa'=>['bail','nullable','numeric'],
            'datos_laboratorio.bilirrubina'=>['bail','nullable','numeric','regex:/^\d{1,7}(\.\d{1,2})?$/'],
            'datos_laboratorio.coagulograma'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.ph'=>['bail','nullable','numeric','regex:/^\d{1,4}(\.\d{1,2})?$/'],
            'datos_laboratorio.co2'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.hco3'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.po2'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.saturacion_oxigeno'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.anion'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_laboratorio.glucosa'=>['bail','nullable','numeric','regex:/^\d{1,7}(\.\d{1,2})?$/'],
            'datos_laboratorio.hemocultivo'=>['bail','nullable','numeric'],
            'datos_laboratorio.urocultivo'=>['bail','nullable','numeric'],
            'datos_laboratorio.descripcion_urocultivo'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_laboratorio.orina'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],

            'datos_electro.archivoElect1'=>['bail','nullable','file','mimes:pdf,jpg,png','max:3072'],
            'datos_electro.archivoElect2'=>['bail','nullable','file','mimes:pdf,jpg,png','max:3072'],
            'datos_electro.archivoElect3'=>['bail','nullable','file','mimes:pdf,jpg,png','max:3072'],
            'datos_electro.descripcion'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_electro.ritmo'=>['bail','nullable','max:50','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_electro.frecuencia_cardiaca'=>['bail','nullable','numeric','max_digits:3'],
            'datos_electro.eje'=>['bail','nullable','regex:/^-?\d{1,3}(\.\d{1,2})?$/'],
            'datos_electro.duracionQRS'=>['bail','nullable','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_electro.ondaP'=>['bail','nullable','max:50','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_electro.ondaT'=>['bail','nullable','max:50','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_electro.segmento'=>['bail','nullable','max:50','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_electro.intervaloPR'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_electro.intervaloQTC'=>['bail','nullable','numeric','regex:/^\d{1,5}(\.\d{1,2})?$/'],
            'datos_electro.conclusion'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_radiografia.archivoRadio1'=>['bail','nullable','file','mimes:pdf,jpg,png','max:3072'],
            'datos_radiografia.archivoRadio2'=>['bail','nullable','file','mimes:pdf,jpg,png','max:3072'],
            'datos_radiografia.archivoRadio3'=>['bail','nullable','file','mimes:pdf,jpg,png','max:3072'],
            'datos_radiografia.partes_blandas'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_radiografia.partes_oseas'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_radiografia.campos_pulmonares'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_radiografia.silueta_cardiovascular'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_radiografia.indice_cardiotoracico'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'datos_radiografia.conclusiones'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],

        ];
    }
    public function messages(): array
    {
        return [
            // Mensajes para los archivos
            'otros.max' => '<p>El campo <strong>Otros estudios</strong> no puede tener más de 255 caracteres.</p>',
            'otros.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Otros estudios</strong>. (Números, emojis, etc)</p>',

            'datos_laboratorio.archivoLab1.uploaded' => '<p>El archivo del <strong>Exámen de Laboratorio </strong>no se ha subido correctamente. Por favor, inténtalo nuevamente.</p>',
            'datos_laboratorio.archivoLab1.file' => '<p>El archivo del <strong>Exámen de Laboratorio </strong> debe ser un archivo válido.</p>',
            'datos_laboratorio.archivoLab1.mimes' => '<p>El archivo del <strong>Exámen de Laboratorio </strong> debe ser de tipo PDF, JPG o PNG.</p>',
            'datos_laboratorio.archivoLab1.max' => '<p>El archivo del <strong>Exámen de Laboratorio </strong> no debe superar los 3 MB.</p>',
            'datos_laboratorio.archivoLab2.file' => 'El archivo de laboratorio 2 debe ser un archivo válido.',
            'datos_laboratorio.archivoLab2.mimes' => 'El archivo de laboratorio 2 debe ser un PDF, JPG o PNG.',
            'datos_laboratorio.archivoLab2.max' => 'El archivo de laboratorio 2 no debe exceder los 3MB.',

            'datos_laboratorio.archivoLab3.file' => 'El archivo de laboratorio 3 debe ser un archivo válido.',
            'datos_laboratorio.archivoLab3.mimes' => 'El archivo de laboratorio 3 debe ser un PDF, JPG o PNG.',
            'datos_laboratorio.archivoLab3.max' => 'El archivo de laboratorio 3 no debe exceder los 3MB.',

            // Mensajes para campos numéricos con regex
            'datos_laboratorio.hematocrito.numeric' => '<p>El campo <strong>Hematocrito</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.hematocrito.regex' => '<p>El campo <strong>Hematocrito</strong> debe tener hasta 5 dígitos enteros y 2 decimales.</p>',
            'datos_laboratorio.leucocitos.numeric' => '<p>El campo <strong>Leucocitos</strong> debe contener únicamente un número válido.</p>',

            'datos_laboratorio.linfocitos.numeric' => '<p>El campo <strong>Linfocitos</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.linfocitos.regex' => '<p>El campo <strong>Linfocitos</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.monocitos.numeric' => '<p>El campo <strong>Monocitos</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.monocitos.regex' => '<p>El campo <strong>Monocitos</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.volumen_corpuscular.numeric' => '<p>El campo <strong>Volumen corpuscular</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.volumen_corpuscular.regex' => '<p>El campo <strong>Volumen corpuscular</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.plaquetas.numeric' => '<p>El campo <strong>Plaquetas</strong> debe contener únicamente un número válido.</p>',

            'datos_laboratorio.glucemia.numeric' => '<p>El campo <strong>Glucemia</strong> debe ser un número.</p>',
            'datos_laboratorio.glucemia.regex' => '<p>El campo <strong>Glucemia</strong> debe tener hasta 7 dígitos y 2 decimales.</p>',

            'datos_laboratorio.urea.numeric' => '<p>El campo <strong>Urea</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.urea.regex' => '<p>El campo <strong>Urea</strong> debe tener un máximo de 7 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.creatinina.numeric' => '<p>El campo <strong>Creatinina</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.creatinina.regex' => '<p>El campo <strong>Creatinina</strong> debe tener un máximo de 7 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.sodio.numeric' => '<p>El campo <strong>Sodio</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.sodio.regex' => '<p>El campo <strong>Sodio</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.potasio.numeric' => '<p>El campo <strong>Potasio</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.potasio.regex' => '<p>El campo <strong>Potasio</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.cloro.numeric' => '<p>El campo <strong>Cloro</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.cloro.regex' => '<p>El campo <strong>Cloro</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.got.numeric' => '<p>El campo <strong>GOT</strong> debe contener únicamente un número válido.</p>',

            'datos_laboratorio.gpt.numeric' => '<p>El campo <strong>GPT</strong> debe contener únicamente un número válido.</p>',

             'datos_laboratorio.fosfatasa.numeric' => '<p>El campo <strong>Fosfatasa</strong> debe contener únicamente un número válido.</p>',

            'datos_laboratorio.bilirrubina.numeric' => '<p>El campo <strong>Bilirrubina</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.bilirrubina.regex' => '<p>El campo <strong>Bilirrubina</strong> debe tener un máximo de 7 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.coagulograma.numeric' => '<p>El campo <strong>Coagulograma</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.coagulograma.regex' => '<p>El campo <strong>Coagulograma</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.ph.numeric' => '<p>El campo <strong>PH</strong> debe contener únicamente un número válido.</p>',


            'datos_laboratorio.ph.regex' => '<p>El campo <strong>PH</strong> debe tener un máximo de 4 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.co2.numeric' => '<p>El campo <strong>CO2</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.co2.regex' => '<p>El campo <strong>CO2</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.hco3.numeric' => '<p>El campo <strong>HCO3</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.hco3.regex' => '<p>El campo <strong>HCO3</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.po2.numeric' => '<p>El campo <strong>PO2</strong> debe contener únicamente un número válido.</p>',

             'datos_laboratorio.po2.regex' => '<p>El campo <strong>PO2</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.saturacion_oxigeno.numeric' => '<p>El campo <strong>Saturación de oxígeno</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.saturacion_oxigeno.regex' => '<p>El campo <strong>Saturación de oxígeno</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.anion.numeric' => '<p>El campo <strong>Anión</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.anion.regex' => '<p>El campo <strong>Anión</strong> debe tener un máximo de 5 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.glucosa.numeric' => '<p>El campo <strong>Glucosa</strong> debe contener únicamente un número válido.</p>',
            'datos_laboratorio.glucosa.regex' => '<p>El campo <strong>Glucosa</strong> debe tener un máximo de 7 dígitos enteros y 2 decimales.</p>',

            'datos_laboratorio.hemocultivo.numeric' => '<p>El campo <strong>Hemocultivo</strong> debe contener únicamente un número válido.</p>',

            'datos_laboratorio.urocultivo.numeric' => '<p>El campo <strong>Urocultivo</strong> debe contener únicamente un número válido.</p>',

            'datos_laboratorio.orina.max' => '<p>El campo <strong>Orina</strong> no debe exceder los 255 caracteres.</p>',
            'datos_laboratorio.orina.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Orina</strong>. (Emojis, etc)</p>',
            'datos_laboratorio.descripcion_urocultivo.max' => '<p>El campo <strong>Descripción del urocultivo</strong> no debe exceder los 255 caracteres.</p>',
            'datos_laboratorio.descripcion_urocultivo.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Descripción del urocultivo</strong>. (Números, emojis, etc)</p>',



            // archivos
            'datos_electro.archivoElect1.file' => 'El archivo 1 debe ser un archivo válido.',
            'datos_electro.archivoElect1.mimes' => 'El archivo 1 debe ser de tipo: pdf, jpg, png.',
            'datos_electro.archivoElect1.max' => 'El archivo 1 no debe superar los 3 MB.',
            
            'datos_electro.archivoElect2.file' => 'El archivo 2 debe ser un archivo válido.',
            'datos_electro.archivoElect2.mimes' => 'El archivo 2 debe ser de tipo: pdf, jpg, png.',
            'datos_electro.archivoElect2.max' => 'El archivo 2 no debe superar los 3 MB.',
            
            'datos_electro.archivoElect3.file' => 'El archivo 3 debe ser un archivo válido.',
            'datos_electro.archivoElect3.mimes' => 'El archivo 3 debe ser de tipo: pdf, jpg, png.',
            'datos_electro.archivoElect3.max' => 'El archivo 3 no debe superar los 3 MB.',

            // descripción
            'datos_electro.descripcion.max' => '<p>La <strong>Descripción general del electrocardiograma</strong> no puede tener más de 255 caracteres.</p>',

            'datos_electro.descripcion.regex'=> '<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Descripción general del electrocardiograma</strong>. (Números, emojis, etc)</p>',

            // ritmo
            'datos_electro.ritmo.max' => '<p>El <strong>Campo ritmo</strong> no puede tener más de 50 caracteres.</p>',

            'datos_electro.ritmo.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Ritmo</strong>. (Emojis, etc)</p>',

            // frecuencia cardiaca
            'datos_electro.frecuencia_cardiaca.numeric' => '<p>La <strong>Frecuencia cardiaca</strong> debe ser un número.</p>',
            'datos_electro.frecuencia_cardiaca.max_digits' => '<p>La <strong>Frecuencia cardiaca</strong> no puede superar los 3 dígitos.</p>',

            // eje
            'datos_electro.eje.regex' => '<p>El campo <strong>Eje</strong> debe contener un número con hasta 3 dígitos y hasta 2 decimales.</p>',

            // duración QRS
            'datos_electro.duracionQRS.regex' => '<p>La <strong>Duración del QRS</strong> debe ser un número válido, con hasta 5 dígitos enteros y 2 decimales.</p>',

            // onda P
            'datos_electro.ondaP.max' => '<p>El campo <strong>Onda P</strong> no puede tener más de 50 caracteres.</p>',
            'datos_electro.ondaP.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Onda P</strong>. (Emojis, etc)</p>',

            // onda T
            'datos_electro.ondaT.max' => '<p>El campo o <strong>Onda T</strong> no puede tener más de 50 caracteres.</p>',
            'datos_electro.ondaT.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Onda T</strong>. (Emojis, etc)</p>',

            // segmento
            'datos_electro.segmento.max' => 'El campo segmento no puede tener más de 50 caracteres.',

            'datos_electro.segmento.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Segmento</strong>. (Emojis, etc)</p>',

            // intervalo PR
            'datos_electro.intervaloPR.numeric' => 'El intervalo PR debe ser un número.',
            'datos_electro.intervaloPR.regex' => 'El intervalo PR debe ser un número válido, con hasta 5 dígitos enteros y 2 decimales.',

            // intervalo QTC
            'datos_electro.intervaloQTC.numeric' => 'El intervalo QTC debe ser un número.',
            'datos_electro.intervaloQTC.regex' => 'El intervalo QTC debe ser un número válido, con hasta 5 dígitos enteros y 2 decimales.',

            // conclusión
            'datos_electro.conclusion.max' => 'La conclusión no puede tener más de 255 caracteres.',
            'datos_electro.conclusion.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en <strong>Conclusión del electrocardiograma</strong>. (Números, emojis, etc)</p>',
             'datos_radiografia.archivoRadio1.file' => 'El archivo 1 debe ser un archivo válido.',

            'datos_radiografia.archivoRadio1.mimes' => 'El archivo 1 debe ser de tipo: pdf, jpg, png.',
            'datos_radiografia.archivoRadio1.max' => 'El archivo 1 no debe superar los 3 MB.',
            
            'datos_radiografia.archivoRadio2.file' => 'El archivo 2 debe ser un archivo válido.',
            'datos_radiografia.archivoRadio2.mimes' => 'El archivo 2 debe ser de tipo: pdf, jpg, png.',
            'datos_radiografia.archivoRadio2.max' => 'El archivo 2 no debe superar los 3 MB.',
            
            'datos_radiografia.archivoRadio3.file' => 'El archivo 3 debe ser un archivo válido.',
            'datos_radiografia.archivoRadio3.mimes' => 'El archivo 3 debe ser de tipo: pdf, jpg, png.',
            'datos_radiografia.archivoRadio3.max' => 'El archivo 3 no debe superar los 3 MB.',

        // partes blandas
        'datos_radiografia.partes_blandas.max' => '<p>El <strong>campo de partes blandas</strong> no puede tener más de 255 caracteres.</p>',
        'datos_radiografia.partes_blandas.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Partes blandas</strong>. (Emojis, etc)</p>',

        // partes óseas
        'datos_radiografia.partes_oseas.max' => '<p>El <strong>campo de partes óseas</strong> no puede tener más de 255 caracteres.</p>',

        'datos_radiografia.partes_oseas.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Partes óseas</strong>. (Emojis, etc)</p>',

        // campos pulmonares
        'datos_radiografia.campos_pulmonares.max' => '<p>El <strong>campo de campos pulmonares</strong> no puede tener más de 255 caracteres.</p>',
        'datos_radiografia.campos_pulmonares.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Campos pulmonares</strong>. (Emojis, etc)</p>',

        // silueta cardiovascular
        'datos_radiografia.silueta_cardiovascular.max' => '<p>El <strong>campo de silueta cardiovascular</strong> no puede tener más de 255 caracteres.</p>',
        'datos_radiografia.silueta_cardiovascular.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Silueta cardiovascular</strong>. (Emojis, etc)</p>',

        // índice cardiotorácico
        'datos_radiografia.indice_cardiotoracico.max' => '<p>El <strong>campo de índice cardiotorácico</strong> no puede tener más de 255 caracteres.</p>',

        'datos_radiografia.indice_cardiotoracico.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Índice cardiotorácico</strong>. (Emojis, etc)</p>',

        // conclusiones
        'datos_radiografia.conclusiones.max' => '<p>El <strong>campo de conclusiones</strong> no puede tener más de 255 caracteres.</p>',
        'datos_radiografia.conclusiones.regex' => '<p>Asegúrese de no haber ingresado caracteres especiales en el campo <strong>Conclusiones de la radiografía de tórax</strong>. (Emojis, etc)</p>',



    'datos_laboratorio.link_laboratorio.max' => '<p>El <strong>Link del estudio de laboratorio</strong> no debe exceder los 255 caracteres.</p>',

    'datos_electro.link_electro.max' => '<p>El <strong>Link del estudio de electrocardiograma</strong> no debe exceder los 255 caracteres.</p>',


    'datos_radiografia.link_radiografia.max' => '<p>El <strong>Link de la radiografía de tórax</strong> no debe exceder los 255 caracteres.</p>',

    'datos_laboratorio.link_laboratorio.url' => '<p>El formato de la <strong>URL de otros estudios de laboratorio</strong> no es válido. Asegúrese de que sea una URL válida.</p>',

    'datos_electro.link_electro.url' => '<p>El formato de la <strong>URL del Electrocardiograma</strong> no es válido. Asegúrese de que sea una URL válida.</p>',

    'datos_radiografia.link_radiografia.url' => '<p>El formato de la <strong>URL de la radiografía de tórax</strong> no es válido. Asegúrese de que sea una URL válida.</p>',

        ];
    }
}
