<?php

namespace App\Http\Requests;

use App\Enums\EstadoCivil;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAspiranteRequest extends FormRequest
{
    /**
     * La aplicación no tiene autenticación (ver docs/DECISIONES.md).
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_completo' => ['required', 'string', 'max:150'],
            'puesto' => ['required', 'string', 'max:150'],
            'estado_civil' => ['required', Rule::enum(EstadoCivil::class)],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'carrera' => ['required', 'string', 'max:150'],
        ];
    }

    public function messages(): array
    {
        return [
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre_completo' => 'nombre completo',
            'puesto' => 'puesto',
            'estado_civil' => 'estado civil',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'carrera' => 'carrera o formación académica',
        ];
    }
}
