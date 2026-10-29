<?php

namespace Database\Factories;

use App\Enums\EstadoCivil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Fábrica de aspirantes falsos para las pruebas: Aspirante::factory()->create()
 *
 * @extends Factory<\App\Models\Aspirante>
 */
class AspiranteFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre_completo' => fake()->name(),
            'puesto' => fake()->jobTitle(),
            'estado_civil' => fake()->randomElement(EstadoCivil::cases()),
            'fecha_nacimiento' => fake()->dateTimeBetween('-60 years', '-18 years'),
            'carrera' => fake()->randomElement(['Ingeniería en Sistemas', 'Matemáticas', 'Física', 'Administración']),
        ];
    }
}
