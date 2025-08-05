<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistroPacienteValidaciones extends FormRequest
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
            'NombrePaciente'=>['bail','required','regex:/^[\pL\s]+$/u','max:100'],
            'EstadoPaciente'=>['bail','required','in:0,1'],
            'PrimerApellidoPaciente'=>['bail','required','regex:/^[\pL\s]+$/u','max:100'],
            'SegundoApellidoPaciente'=>['bail','nullable','regex:/^[\pL\s]+$/u','max:100'],
            'CurpPaciente'=> ['bail','nullable', 'regex:/^[A-Z]{1}[AEIOU]{1}[A-Z]{2}[0-9]{2}(0[1-9]|1[0-2])(0[1-9]|[12][0-9]|3[01])[HM](AS|BC|BS|CC|CL|CM|CS|CH|DF|DG|GT|GR|HG|JC|MC|MN|MS|NT|NL|OC|PL|QT|QR|SP|SL|SR|TC|TS|TL|VZ|YN|ZS|NE)[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z]{1}[0-9]$/i','max:18'],
            'GeneroPaciente'=>['bail','required','numeric'],
            'FechaNacimientoPaciente'=>['bail','required','date'],
            'EdadPaciente'=>['bail','required'],
            'OcupacionPaciente'=>['bail','required','regex:/^[\pL\pNñÑ\s,.-]+$/u','max:100'],
            'EstadoCivilPaciente'=>['bail','required','numeric'],
            'NacionalidadPaciente'=>['bail','required','numeric'],
            'TipoSangrePaciente'=>['bail','nullable','numeric'],
            'CorreoPaciente'=>['bail','nullable', 'email'],
            'TelefonoPaciente'=>['bail','nullable','regex:/^[a-zA-Z0-9\s\-]+$/'],
            'CelularPaciente'=>['bail','nullable','regex:/^[a-zA-Z0-9\s\-]+$/'],
        ];
    }
    public function messages(): array
    {
        return [
            'required'=>'Ningun campo obligatorio debe ir vacio',
            'EstadoPaciente.required'=>'<p>Asegúrese de seleccionar el <strong>Estado del paciente.</strong></p>',
            'EstadoPaciente.in'=>'<p>Asegúrese de seleccionar un <strong>Estado del paciente (Activo o Inactivo)</strong> válido</p>',

            'NombrePaciente.regex'=>'<p>Asegúrese de no haber ingresado caracteres especiales en el <strong>Nombre del paciente</strong>. (Números, emojis, etc)</p>',
            'NombrePaciente.max'=>'<p>Asegúrese de que el <strong>Nombre del paciente</strong> no tenga más de 100 caracteres.</p>',
            'PrimerApellidoPaciente.regex'=>'<p>Asegúrese de no haber ingresado caracteres especiales en el <strong>Primer apellido del paciente</strong>. (Números, emojis, etc)</p>',
            'PrimerApellidoPaciente.max'=>'<p>Asegúrese de que el <strong>Primer apellido del paciente</strong> no tenga más de 100 caracteres.</p>',
            'SegundoApellidoPaciente.regex'=>'<p>Asegúrese de no haber ingresado caracteres especiales en el <strong>Segundo apellido del paciente</strong>. (Números, emojis, etc)</p>',
            'SegundoApellidoPaciente.max'=>'<p>Asegúrese de que el <strong>Segundo apellido del paciente</strong> no tenga más de 100 caracteres.</p>',
            'CurpPaciente.regex'=>'<p>Asegúrese de ingresar un <strong>CURP</strong> válido.</p>',
            'CurpPaciente.max'=>'<p>Asegúrese de que el <strong>Segundo apellido del paciente</strong> no tenga más de 18 caracteres.</p>',
            'GeneroPaciente.numeric'=>'<p>Asegúrese de ingresar un <strong>Género</strong> válido.</p>',
            'FechaNacimientoPaciente.date'=>'<p>La <strong>fecha de nacimiento del paciente</strong> no tiene un formato válido</p>',
            'OcupacionPaciente.regex'=>'<p>Asegúrese de no haber ingresado caracteres especiales en la <strong>Ocupación del paciente</strong>. (Números, emojis, etc)</p>',
            'OcupacionPaciente.max'=>'<p>Asegúrese de que la <strong>Ocupación del paciente</strong> no tenga más de 100 caracteres.</p>',
            'EstadoCivilPaciente.numeric'=>'<p>Asegúrese de que el <strong>Estado civil del paciente</strong> tenga un formato válido.</p>',
            'NacionalidadPaciente.numeric'=>'<p>Asegúrese de que la <strong>Nacionalidad del paciente</strong> tenga un formato válido.</p>',
            'TipoSangrePaciente.numeric'=>'<p>Asegúrese de que el <strong>Tipo de sangre</strong> tenga un formato válido.</p>',
            'CorreoPaciente.email'=>'<p>Asegúrese de que el <strong>Correo del paciente</strong> tenga un formato válido.</p>',
            'TelefonoPaciente.regex'=>'<p>Asegúrese de que el <strong>Número de teléfono del paciente</strong> tenga un formato válido.</p>',
            'CelularPaciente.regex'=>'<p>Asegúrese de que el número de <strong>Teléfono celular</strong> tenga un formato válido.</p>',

        ];
    }
}
