<?php

namespace App\Enums;

/**
 * Catálogo cerrado de estados civiles.
 *
 * Es un "backed enum": cada caso tiene un valor de texto (el que se guarda en la BD).
 * Así evitamos que se guarden valores mal escritos como "casado", "Casada " o "kasado".
 */
enum EstadoCivil: string
{
    case Soltero = 'soltero';
    case Casado = 'casado';
    case UnionLibre = 'union_libre';
    case Divorciado = 'divorciado';
    case Viudo = 'viudo';
    case Separado = 'separado';

    /**
     * Texto que se muestra al usuario en pantalla.
     */
    public function etiqueta(): string
    {
        return match ($this) {
            self::Soltero => 'Soltero/a',
            self::Casado => 'Casado/a',
            self::UnionLibre => 'Unión libre',
            self::Divorciado => 'Divorciado/a',
            self::Viudo => 'Viudo/a',
            self::Separado => 'Separado/a',
        };
    }
}
