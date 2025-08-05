<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditarInformacionMedicoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    protected $stopOnFirstFailure = true;

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
            'email' => ['bail','required', 'email', 'max:255'],
            'nombre' => ['bail','required', 'string', 'max:100', 'regex:/^[\p{L}\p{N}\s]+$/u'],
            'primer_apellido' => ['bail','required', 'string', 'max:100', 'regex:/^[\p{L}\p{N}\s]+$/u'],
            'segundo_apellido' => ['bail','nullable', 'string', 'max:100', 'regex:/^[\p{L}\p{N}\s]+$/u'],
            'no_cuenta' => ['bail','required',  'max:20', 'regex:/^[a-zA-Z0-9-]+$/'],
            'licenciatura' => ['bail','required', 'string', 'max:100', 'regex:/^[\p{L}\p{N}\s]+$/u'],
            'motivoCorreccion' => ['bail','required', 'string', 'max:100', 'regex:/^[\p{L}\p{N}\s]+$/u'],
            'motivoCambioSede' => 'required_if:cambioSede,1|nullable|string|max:255',
            'evidenciaCambioSede' => 'required_if:cambioSede,1|nullable|mimes:pdf,jpg,jpeg,png|max:3072',
            'cambioSede' => 'nullable|boolean',
            'fecha_inicio' => ['bail', 'required', 'date', 'date_format:Y-m-d', 'before_or_equal:fecha_termino'],
            'fecha_termino' => ['bail', 'required', 'date', 'date_format:Y-m-d', 'after_or_equal:fecha_inicio']
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras, números y espacios.',
            'primer_apellido.required' => 'El primer apellido es obligatorio.',
            'primer_apellido.regex' => 'El primer apellido solo puede contener letras, números y espacios.',
            'segundo_apellido.regex' => 'El segundo apellido solo puede contener letras, números y espacios.',
            'no_cuenta.required' => 'El número de cuenta es obligatorio.',
            'no_cuenta.regex' => 'El número de cuenta solo puede contener letras, números y guiones (-).',
            'licenciatura.required' => 'La licenciatura es obligatoria.',
            'licenciatura.regex' => 'La licenciatura solo puede contener letras, números y espacios.',
            'motivoCorreccion.required' => 'Debes ingresar un motivo de corrección cuando se realiza un cambio.',
            'motivoCorreccion.regex' => 'El motivo de corrección solo puede contener letras, números y espacios.',
            'motivoCambioSede.required_if' => 'El motivo del cambio / eliminación de sede es obligatorio cuando se realiza un cambio',
            'evidenciaCambioSede.required_if' => 'La evidencia del cambio de sede es obligatoria cuando se realiza un cambio',
            'evidenciaCambioSede.mimes' => 'La evidencia debe ser un archivo PDF o una imagen (JPG, JPEG, PNG)',
            'evidenciaCambioSede.max' => 'La evidencia no debe superar los 3MB',
            'evidenciaCambioSede.uploaded' => 'La evidencia del cambio de sede no pudo ser cargada',
            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_inicio.date_format' => 'El formato de la fecha de inicio debe ser YYYY-MM-DD.',
            'fecha_inicio.before_or_equal' => 'La fecha de inicio debe ser anterior o igual a la fecha de término.',
            'fecha_termino.required' => 'La fecha de término es obligatoria.',
            'fecha_termino.date' => 'La fecha de término debe ser una fecha válida.',
            'fecha_termino.date_format' => 'El formato de la fecha de término debe ser YYYY-MM-DD.',
            'fecha_termino.after_or_equal' => 'La fecha de término debe ser posterior o igual a la fecha de inicio.'
        ];
    }
} 