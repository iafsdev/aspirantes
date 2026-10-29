<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Se usa tanto al crear como al editar la evaluación: las reglas son las mismas.
 */
class StoreEvaluacionRequest extends FormRequest
{
    private const CALIFICACIONES = ['conocimientos_tecnicos', 'referencias', 'habilidades_blandas'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $reglas = [
            'comentarios' => ['nullable', 'string', 'max:5000'],
        ];

        foreach (self::CALIFICACIONES as $campo) {
            $reglas[$campo] = ['bail', 'required', 'numeric', 'between:0,10', 'decimal:0,2'];
        }

        return $reglas;
    }

    public function messages(): array
    {
        $mensajes = [];

        foreach (self::CALIFICACIONES as $campo) {
            $mensajes["{$campo}.between"] = 'La calificación de :attribute debe estar entre 0 y 10.';
            $mensajes["{$campo}.decimal"] = 'La calificación de :attribute admite como máximo 2 decimales.';
        }

        return $mensajes;
    }

    public function attributes(): array
    {
        return [
            'conocimientos_tecnicos' => 'conocimientos técnicos',
            'referencias' => 'referencias',
            'habilidades_blandas' => 'habilidades blandas',
            'comentarios' => 'comentarios',
        ];
    }
}
