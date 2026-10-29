<?php

namespace App\Models;

use App\Enums\EstadoCivil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Aspirante extends Model
{
    use HasFactory;

    /**
     * Columnas que se pueden llenar con create()/update().
     * Protege contra "asignación masiva": si alguien manda un campo extra (ej. id), se ignora.
     */
    protected $fillable = [
        'nombre_completo',
        'puesto',
        'estado_civil',
        'fecha_nacimiento',
        'carrera',
    ];

    /**
     * Conversión automática de tipos al leer/escribir.
     * estado_civil llega como objeto EstadoCivil y fecha_nacimiento como fecha (Carbon).
     */
    protected function casts(): array
    {
        return [
            'estado_civil' => EstadoCivil::class,
            'fecha_nacimiento' => 'date',
        ];
    }

    /**
     * Un aspirante tiene (como máximo) una evaluación.
     * Uso: $aspirante->evaluacion
     */
    public function evaluacion(): HasOne
    {
        return $this->hasOne(Evaluacion::class);
    }
}
