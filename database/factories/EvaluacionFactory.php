<?php

namespace Database\Factories;

use App\Models\Aspirante;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Evaluacion>
 */
class EvaluacionFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Si no se indica aspirante, crea uno nuevo automáticamente.
            'aspirante_id' => Aspirante::factory(),
            'conocimientos_tecnicos' => fake()->randomFloat(2, 0, 10),
            'referencias' => fake()->randomFloat(2, 0, 10),
            'habilidades_blandas' => fake()->randomFloat(2, 0, 10),
            'comentarios' => fake()->sentence(),
        ];
    }
}
