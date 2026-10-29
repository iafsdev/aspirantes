<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluacion extends Model
{
    use HasFactory;

    /**
     * Laravel pluraliza en inglés ("evaluacions"), así que indicamos el nombre real de la tabla.
     */
    protected $table = 'evaluaciones';

    protected $fillable = [
        'conocimientos_tecnicos',
        'referencias',
        'habilidades_blandas',
        'comentarios',
    ];

    protected function casts(): array
    {
        return [
            'conocimientos_tecnicos' => 'decimal:2',
            'referencias' => 'decimal:2',
            'habilidades_blandas' => 'decimal:2',
        ];
    }

    /**
     * La evaluación pertenece a un aspirante (aquí vive la llave foránea aspirante_id).
     */
    public function aspirante(): BelongsTo
    {
        return $this->belongsTo(Aspirante::class);
    }

    /**
     * Atributo calculado (no existe en la BD): $evaluacion->promedio
     * Promedio simple de las tres calificaciones, redondeado a 2 decimales.
     */
    protected function promedio(): Attribute
    {
        return Attribute::get(fn () => round(
            ($this->conocimientos_tecnicos + $this->referencias + $this->habilidades_blandas) / 3,
            2
        ));
    }
}
