<?php

namespace Database\Seeders;

use App\Enums\EstadoCivil;
use App\Models\Aspirante;
use Illuminate\Database\Seeder;

/**
 * Datos de ejemplo. NO corre en el arranque; solo a mano:
 *   php artisan db:seed --class=DatosEjemploSeeder
 *
 * Los datos están escritos a mano (sin factories) porque en Docker se instala sin
 * dependencias de desarrollo y Faker no estaría disponible.
 */
class DatosEjemploSeeder extends Seeder
{
    public function run(): void
    {
        $aspirantes = [
            ['María Fernanda López Ruiz', 'Desarrolladora Full Stack', EstadoCivil::Soltero, '1995-04-12', 'Ingeniería en Sistemas', [9.5, 8.75, 9.0, 'Excelente dominio de Laravel.']],
            ['José Luis Hernández Pérez', 'Analista de Datos', EstadoCivil::Casado, '1990-09-03', 'Matemáticas Aplicadas', [8.0, 9.25, 7.5, 'Buenas referencias de su empleo anterior.']],
            ['Ana Sofía Martínez García', 'Desarrolladora Full Stack', EstadoCivil::UnionLibre, '1998-01-27', 'Ciencias de la Computación', null],
            ['Carlos Alberto Ramírez Soto', 'Administrador de Sistemas', EstadoCivil::Divorciado, '1987-11-15', 'Ingeniería en Telecomunicaciones', [7.25, 6.5, 8.0, null]],
        ];

        foreach ($aspirantes as [$nombre, $puesto, $estadoCivil, $fecha, $carrera, $evaluacion]) {
            $aspirante = Aspirante::create([
                'nombre_completo' => $nombre,
                'puesto' => $puesto,
                'estado_civil' => $estadoCivil,
                'fecha_nacimiento' => $fecha,
                'carrera' => $carrera,
            ]);

            // Uno se queda sin evaluar para ver ambos casos en la interfaz.
            if ($evaluacion) {
                [$tecnicos, $referencias, $blandas, $comentarios] = $evaluacion;
                $aspirante->evaluacion()->create([
                    'conocimientos_tecnicos' => $tecnicos,
                    'referencias' => $referencias,
                    'habilidades_blandas' => $blandas,
                    'comentarios' => $comentarios,
                ]);
            }
        }
    }
}
