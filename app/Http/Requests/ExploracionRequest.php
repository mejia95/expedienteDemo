<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExploracionRequest extends FormRequest
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
            'tension_sistolica' => ['bail','nullable','regex:/^\d{1,3}(\.\d{1,3})?$/'],
            'glucosa_sanguinea' => ['bail','nullable','regex:/^\d{1,3}(\.\d{1,3})?$/'],
            'tension_diastolica' => ['bail','nullable','required_unless:tension_sistolica,null','regex:/^\d{1,3}(\.\d{1,3})?$/'],
            'frecuencia_cardiaca' => ['bail','nullable','numeric','regex:/^\d{1,3}(\.\d{1,3})?$/'],
            'frecuencia_respiratoria' => ['bail','nullable','regex:/^\d{1,3}(\.\d{1,3})?$/'],
            'temperatura' => ['bail','required','numeric','min:35','max:42', 'regex:/^\d{2}(\.\d{1,2})?$/'],
            'peso' => ['bail','required','numeric','regex:/^\d{1,4}(\.\d{1,3})?$/'],
            'peso_unidad' => ['bail','required','in:g,kg'],
            'altura' => ['bail','required','regex:/^\d{1,3}(\.\d{1,2})?$/'],
            'altura_unidad' => ['bail','required','in:cm,m'],
            'imc' => ['bail','nullable','regex:/^\d{1,3}(\.\d{1,3})?$/'],
            'imc_unidad' => ['bail','nullable','in:Kg/m2,g/cm2'],
            'motivo_consulta' => ['bail','required','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'piel.aspecto'=>['bail','nullable','max:255'],
            'piel.descripcion_piel'=> ['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'piel.distribucion_pilosa'=>['bail','nullable','max:255'],
            'piel.faneras'=>['bail','nullable','max:255'],
            'piel.lesiones'=>['bail','nullable','max:255'],
            'piel.tejido_celular'=>['bail','nullable','max:255'],
            'cabeza.CraneoCara'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'cabeza.CueroCabelludo'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'cabeza.RegionOrbitoNasal'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'cabeza.RegionOrofaringea'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],

            'cabeza.InspeccionCuello'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'cabeza.PalpacionCuello'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'cabeza.PercusionCuello'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'cabeza.AuscultacionCuello'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],

            'oftalmologico.RefraccionOjoDerecho'=>['bail','nullable','max:50','regex:/^[\p{L}\p{M}\p{N}\p{P}°= -x.]+$/u'],
            'oftalmologico.RefraccionOjoIzquierdo'=>['bail','nullable','max:50','regex:/^[\p{L}\p{M}\p{N}\p{P}°= -x.]+$/u'],

            'oftalmologico.ExploracionSegmentoAnterior'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'oftalmologico.ExploracionRetinayVitreo'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],

            'respiratorio.descripcion_respiratorio'=>['bail','nullable','max:255'],

            'respiratorio.InspeccionRespiratorio'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'respiratorio.PalpacionRespiratorio'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'respiratorio.PercusionRespiratorio'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'respiratorio.AuscultacionRespiratorio'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],

            'cardiovascular.descripcion_cardiovascular'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'abdomen.InspeccionAbdomen'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'abdomen.PalpacionAbdomen'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'abdomen.PercusionAbdomen'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'abdomen.AuscultacionAbdomen'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],

            'neurologico.ocular'=>['bail','nullable','numeric'],
            'neurologico.verbal'=>['bail','nullable','numeric'],
            'neurologico.motora'=>['bail','nullable','numeric'],
            'neurologico.total'=>['bail','nullable','numeric'],
            'neurologico.motilidad_activa'=>['bail','nullable','max:255'],
            'neurologico.motilidad_pasiva'=>['bail','nullable','max:255'],
            'neurologico.motilidad_refleja'=>['bail','nullable','max:255'],
            'neurologico.pares_cranales'=>['bail','nullable','max:255'],
            'neurologico.sensibilidad'=>['bail','nullable','max:255'],
            'neurologico.descripcion_neurologico'=>['bail','nullable','max:255'],
            'neurologico.FuncionesMentalesSuperiores'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'neurologico.ParesCranales'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'neurologico.EsferaSensitiva'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],

            'GinecoObstetrico.Mamas'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'GinecoObstetrico.Abdomen'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
            'GinecoObstetrico.Pelvis'=>['bail','nullable','max:255','regex:/^[\p{L}\p{M}\p{Zs}\p{P}\n\d]+$/u'],
        ];
    }
    public function messages(): array
    {
        return[
        'tension_sistolica.regex' => '<p>El campo de <strong>Tensión sistólica</strong> debe tener hasta 3 dígitos enteros y 3 decimales.</p>',
        'glucosa_sanguinea.regex' => '<p>El campo de <strong>Glucosa sanguínea</strong> debe tener hasta 3 dígitos enteros, sin emojis ni caracteres especiales.</p>',

        'tension_diastolica.regex' => '<p>El campo de <strong>Tensión diastólica</strong> debe tener hasta 3 dígitos enteros y 3 decimales.</p>',
        'tension_diastolica.required_unless' => '<p>Debe ingresar el campo de <strong>Tensión diastólica</strong>.</p>',

        'frecuencia_cardiaca.regex' => '<p>El campo de <strong>Frecuencia cardíaca</strong> debe tener hasta 3 dígitos enteros y 3 decimales.</p>',

        'frecuencia_respiratoria.regex' => '<p>El campo de <strong>Frecuencia respiratoria</strong> debe tener hasta 3 dígitos enteros y 3 decimales.</p>',

        'temperatura.numeric' => '<p>El campo de <strong>Temperatura</strong> debe ser un número.</p>',
        'temperatura.min' => '<p>El campo de <strong>Temperatura</strong> debe ser al menos 35°C.</p>',
        'temperatura.max' => '<p>El campo de <strong>Temperatura</strong> no debe exceder 42°C.</p>',
        'temperatura.regex' => '<p>El campo de <strong>Temperatura</strong> debe tener hasta dos decimales.</p>',
        'temperatura.required' => '<p>El campo de <strong>Temperatura</strong> del paciente es obligatoria.</p>',

        'peso.required' => '<p>El campo <strong>Peso</strong> del paciente es obligatorio.</p>',
        'peso.numeric' => '<p>El campo <strong>Peso</strong> debe ser un número.</p>',
        'peso.regex' => '<p>El campo <strong>Peso</strong> debe tener hasta 4 dígitos enteros y 3 decimales.</p>',

        'peso_unidad.required' => '<p>El campo <strong>Unidad de peso</strong> es obligatorio.</p>',
        'peso_unidad.in' => '<p>El campo <strong>Unidad de peso</strong> debe ser "g" o "kg".</p>',

        'altura.required' => '<p>El campo <strong>Altura</strong> es obligatorio.</p>',
        'altura.regex' => '<p>El campo <strong>Altura</strong> debe tener hasta 3 dígitos enteros y 2 decimales.</p>',

        'altura_unidad.required' => '<p>El campo <strong>Unidad de altura</strong> es obligatorio.</p>',
        'altura_unidad.in' => '<p>El campo <strong>Unidad de altura</strong> debe ser "cm" o "m".</p>',

        'imc.regex' => '<p>El campo <strong>IMC</strong> debe tener hasta 3 dígitos enteros y 3 decimales.</p>',

        'imc_unidad.in' => '<p>El campo <strong>Unidad de IMC</strong> debe ser "Kg/m2" o "g/cm2".</p>',

        'motivo_consulta.required' => '<p>El <strong>Motivo de consulta</strong> es obligatorio.</p>',
        'motivo_consulta.regex' => '<p>El campo <strong>Motivo de consulta</strong> solo puede contener letras, espacios y signos de puntuación básicos, sin emojis ni caracteres especiales.</p>',
        'motivo_consulta.max' => '<p>El <strong>Motivo de consulta</strong> no debe exceder los 255 caracteres.</p>',

        'piel.aspecto.max' => 'El aspecto de la piel no debe exceder los 255 caracteres.',

        'piel.descripcion_piel.max' => '<p>El campo <strong>Descripción de la piel</strong> no puede tener más de 255 caracteres.</p>',
        'piel.descripcion_piel.regex' => '<p>El campo <strong>Descripción de la piel</strong> contiene caracteres inválidos. Solo se permiten letras, números, puntuación y espacios.</p>',

        'piel.distribucion_pilosa.max' => 'La distribución pilosa no debe exceder los 255 caracteres.',
        'piel.faneras.max' => 'Las faneras no deben exceder los 255 caracteres.',
        'piel.lesiones.max' => 'Las lesiones no deben exceder los 255 caracteres.',
        'piel.tejido_celular.max' => 'El tejido celular no debe exceder los 255 caracteres.',

        'cabeza.CraneoCara.max' => '<p>El campo <strong>Cráneo y cara</strong> no debe exceder los 255 caracteres.</p>',
        'cabeza.CraneoCara.regex' => '<p>El campo <strong>Cráneo y cara</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',
        'cabeza.CueroCabelludo.max' => '<p>El campo <strong>Cuero cabelludo</strong> no debe exceder los 255 caracteres.</p>',
        'cabeza.CueroCabelludo.regex' => '<p>El campo <strong>Cuero cabelludo</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'cabeza.RegionOrbitoNasal.max' => '<p>El campo <strong>Región orbito-nasal</strong> no debe exceder los 255 caracteres.</p>',
        'cabeza.RegionOrbitoNasal.regex' => '<p>El campo <strong>Región orbito-nasal</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'cabeza.RegionOrofaringea.max' => '<p>El campo <strong>Región orofaringea</strong> no debe exceder los 255 caracteres.</p>',
        'cabeza.RegionOrofaringea.regex' => '<p>El campo <strong>Región orofaringea</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'cabeza.InspeccionCuello.max' => '<p>El campo <strong>Inspección del cuello</strong> no debe exceder los 255 caracteres.</p>',
        'cabeza.InspeccionCuello.regex' => '<p>El campo <strong>Inspección del cuello</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'cabeza.PalpacionCuello.max' => '<p>El campo <strong>Palpación del cuello</strong> no debe exceder los 255 caracteres.</p>',
        'cabeza.PalpacionCuello.regex' => '<p>El campo <strong>Palpación del cuello</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'cabeza.PercusionCuello.max' => '<p>El campo <strong>Percusión del cuello</strong> no debe exceder los 255 caracteres.</p>',
        'cabeza.PercusionCuello.regex' => '<p>El campo <strong>Percusión del cuello</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'cabeza.AuscultacionCuello.max' => '<p>El campo <strong>Auscultación del cuello</strong> no debe exceder los 255 caracteres.</p>',
        'cabeza.AuscultacionCuello.regex' => '<p>El campo <strong>Auscultación del cuello</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'oftalmologico.RefraccionOjoDerecho.max' => '<p>El campo <strong>Refracción del ojo derecho</strong> no puede tener más de 50 caracteres.</p>',
        'oftalmologico.RefraccionOjoDerecho.regex' => '<p>El campo <strong>Refracción del ojo derecho</strong> contiene caracteres no válidos. Por favor, verifique la información ingresada.</p>',

        'oftalmologico.RefraccionOjoIzquierdo.max' => '<p>El campo <strong>Refracción del ojo izquierdo</strong> no puede tener más de 50 caracteres.</p>',
        'oftalmologico.RefraccionOjoIzquierdo.regex' => '<p>El campo <strong>Refracción del ojo izquierdo</strong> contiene caracteres no válidos. Por favor, verifique la información ingresada.</p>',
        'oftalmologico.ExploracionSegmentoAnterior.max' => '<p>El campo <strong>Exploración del Segmento Anterior</strong> no puede tener más de 255 caracteres.</p>',
        'oftalmologico.ExploracionSegmentoAnterior.regex' => '<p>El campo <strong>Exploración del Segmento Anterior</strong> solo puede contener letras, números, caracteres especiales y saltos de línea.</p>',
        'oftalmologico.ExploracionRetinayVitreo.max' => '<p>El campo <strong>Exploración Retina y Vítreo</strong> no puede tener más de 255 caracteres.</p>',
        'oftalmologico.ExploracionRetinayVitreo.regex' => '<p>El campo <strong>Exploración Retina y Vítreo</strong> solo puede contener letras, números, caracteres especiales y saltos de línea.</p>',


        'respiratorio.InspeccionRespiratorio.max' => '<p>El campo <strong>Inspección de la sección respiratorio</strong> no debe exceder los 255 caracteres.</p>',
        'respiratorio.InspeccionRespiratorio.regex' => '<p>El campo <strong>Inspección de la sección respiratorio</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'respiratorio.PalpacionRespiratorio.max' => '<p>El campo <strong>Palpación de la sección respiratorio</strong> no debe exceder los 255 caracteres.</p>',
        'respiratorio.PalpacionRespiratorio.regex' => '<p>El campo <strong>Palpación de la sección respiratorio</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'respiratorio.PercusionRespiratorio.max' => '<p>El campo <strong>Percusión de la sección respiratorio</strong> no debe exceder los 255 caracteres.</p>',
        'respiratorio.PercusionRespiratorio.regex' => '<p>El campo <strong>Percusión de la sección respiratorio</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'respiratorio.AuscultacionRespiratorio.max' => '<p>El campo <strong>Auscultación de la sección respiratorio</strong> no debe exceder los 255 caracteres.</p>',
        'respiratorio.AuscultacionRespiratorio.regex' => '<p>El campo <strong>Auscultación de la sección respiratorio</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'cardiovascular.descripcion_cardiovascular.max' => '<p>El campo <strong>Descripción cardiovascular</strong> no debe exceder los 255 caracteres.</p>',
        'cardiovascular.descripcion_cardiovascular.regex' => '<p>El campo <strong>Descripción cardiovascular</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',
        'abdomen.descripcion_abdomen.max' => 'La descripción del abdomen no debe exceder los 255 caracteres.',
        'abdomen.InspeccionAbdomen.max' => '<p>El campo <strong>Inspección de la sección abdomen</strong> no debe exceder los 255 caracteres.</p>',
        'abdomen.InspeccionAbdomen.regex' => '<p>El campo <strong>Inspección de la sección abdomen</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'abdomen.PalpacionAbdomen.max' => '<p>El campo <strong>Palpación de la sección abdomen</strong> no debe exceder los 255 caracteres.</p>',
        'abdomen.PalpacionAbdomen.regex' => '<p>El campo <strong>Palpación de la sección abdomen</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'abdomen.PercusionAbdomen.max' => '<p>El campo <strong>Percusión de la sección abdomen</strong> no debe exceder los 255 caracteres.</p>',
        'abdomen.PercusionAbdomen.regex' => '<p>El campo <strong>Percusión de la sección abdomen</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'abdomen.AuscultacionAbdomen.max' => '<p>El campo <strong>Auscultación de la sección abdomen</strong> no debe exceder los 255 caracteres.</p>',
        'abdomen.AuscultacionAbdomen.regex' => '<p>El campo <strong>Auscultación de la sección abdomen</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',


        'neurologico.ocular.numeric' => 'El valor ocular debe ser un número.',
        'neurologico.verbal.numeric' => 'El valor verbal debe ser un número.',
        'neurologico.motora.numeric' => 'El valor motora debe ser un número.',
        'neurologico.total.numeric' => 'El valor total debe ser un número.',

        'neurologico.motilidad_activa.max' => 'La descripción de la motilidad activa no debe exceder los 255 caracteres.',
        'neurologico.motilidad_pasiva.max' => 'La descripción de la motilidad pasiva no debe exceder los 255 caracteres.',
        'neurologico.motilidad_refleja.max' => 'La descripción de la motilidad refleja no debe exceder los 255 caracteres.',
        'neurologico.pares_cranales.max' => 'La descripción de los pares craneales no debe exceder los 255 caracteres.',
        'neurologico.sensibilidad.max' => 'La descripción de la sensibilidad no debe exceder los 255 caracteres.',
        'neurologico.descripcion_neurologico.max' => 'La descripción neurológica no debe exceder los 255 caracteres.',

        'neurologico.FuncionesMentalesSuperiores.max' => '<p>El campo <strong>Funciones mentales superiores de la sección neurológico</strong> no debe exceder los 255 caracteres.</p>',
        'neurologico.FuncionesMentalesSuperiores.regex' => '<p>El campo <strong>Funciones mentales superiores de la sección neurológico</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'neurologico.ParesCranales.max' => '<p>El campo <strong>Pares craneales de la sección neurológico</strong> no debe exceder los 255 caracteres.</p>',
        'neurologico.ParesCranales.regex' => '<p>El campo <strong>Pares craneales de la sección neurológico</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'neurologico.EsferaSensitiva.max' => '<p>El campo <strong>Esfera sensitiva de la sección neurológico</strong> no debe exceder los 255 caracteres.</p>',
        'neurologico.EsferaSensitiva.regex' => '<p>El campo <strong>Esfera sensitiva de la sección neurológico</strong> solo puede contener letras, espacios, signos de puntuación básicos y saltos de línea.</p>',

        'GinecoObstetrico.Mamas.max' => '<p>El campo <strong>Mamas</strong> no puede exceder los 255 caracteres.</p>',
        'GinecoObstetrico.Mamas.regex' => '<p>El campo <strong>Mamas</strong> contiene caracteres no válidos.</p>',

        'GinecoObstetrico.Abdomen.max' => '<p>El campo <strong>Abdomen</strong> no puede exceder los 255 caracteres.</p>',
        'GinecoObstetrico.Abdomen.regex' => '<p>El campo <strong>Abdomen</strong> contiene caracteres no válidos.</p>',

        'GinecoObstetrico.Pelvis.max' => '<p>El campo <strong>Pelvis</strong> no puede exceder los 255 caracteres.</p>',
        'GinecoObstetrico.Pelvis.regex' => '<p>El campo <strong>Pelvis</strong> contiene caracteres no válidos.</p>',
        ];
    }
}
