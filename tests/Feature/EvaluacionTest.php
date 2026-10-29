<?php

namespace Tests\Feature;

use App\Models\Aspirante;
use App\Models\Evaluacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class EvaluacionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Formulario válido; cada prueba cambia solo el campo que quiere probar.
     */
    private function datosValidos(array $cambios = []): array
    {
        return array_merge([
            'conocimientos_tecnicos' => '9.50',
            'referencias' => '8.75',
            'habilidades_blandas' => '7',
            'comentarios' => 'Buen candidato.',
        ], $cambios);
    }

    public function test_muestra_el_formulario_de_evaluacion(): void
    {
        $aspirante = Aspirante::factory()->create();

        $this->get(route('aspirantes.evaluacion.create', $aspirante))
            ->assertOk()
            ->assertSee('Evaluar aspirante')
            ->assertSee('step="0.01"', false);
    }

    public function test_registra_una_evaluacion_con_decimales(): void
    {
        // Preparar
        $aspirante = Aspirante::factory()->create();

        // Actuar
        $this->post(route('aspirantes.evaluacion.store', $aspirante), $this->datosValidos())
            ->assertRedirect(route('aspirantes.show', $aspirante))
            ->assertSessionHas('exito');

        // Verificar: los decimales se guardaron tal cual
        $this->assertDatabaseHas('evaluaciones', [
            'aspirante_id' => $aspirante->id,
            'conocimientos_tecnicos' => 9.50,
            'referencias' => 8.75,
            'habilidades_blandas' => 7.00,
        ]);
    }

    public function test_acepta_los_limites_del_rango(): void
    {
        $aspirante = Aspirante::factory()->create();

        // 0 y 10 son válidos (el rango es inclusivo)
        $this->post(route('aspirantes.evaluacion.store', $aspirante), $this->datosValidos([
            'conocimientos_tecnicos' => '0',
            'referencias' => '10',
        ]))->assertSessionHasNoErrors();
    }

    public function test_los_comentarios_son_opcionales(): void
    {
        $aspirante = Aspirante::factory()->create();

        $this->post(route('aspirantes.evaluacion.store', $aspirante), $this->datosValidos(['comentarios' => '']))
            ->assertSessionHasNoErrors();

        $this->assertNull($aspirante->evaluacion->comentarios);
    }

    /**
     * Cada caso ejecuta test_rechaza_calificaciones_invalidas una vez.
     */
    public static function calificacionesInvalidas(): array
    {
        return [
            'negativa' => ['-1'],
            'mayor que 10' => ['10.5'],
            'texto' => ['abc'],
            'más de 2 decimales' => ['8.123'],
            'vacía' => [''],
        ];
    }

    #[DataProvider('calificacionesInvalidas')]
    public function test_rechaza_calificaciones_invalidas(string $valor): void
    {
        $aspirante = Aspirante::factory()->create();

        $this->post(route('aspirantes.evaluacion.store', $aspirante), $this->datosValidos(['referencias' => $valor]))
            ->assertSessionHasErrors('referencias');

        // No se guardó nada
        $this->assertDatabaseCount('evaluaciones', 0);
    }

    public function test_el_mensaje_de_rango_es_claro(): void
    {
        $aspirante = Aspirante::factory()->create();

        $this->post(route('aspirantes.evaluacion.store', $aspirante), $this->datosValidos(['conocimientos_tecnicos' => '10.5']))
            ->assertSessionHasErrors([
                'conocimientos_tecnicos' => 'La calificación de conocimientos técnicos debe estar entre 0 y 10.',
            ]);
    }

    public function test_si_ya_tiene_evaluacion_el_formulario_de_alta_redirige_a_editar(): void
    {
        $evaluacion = Evaluacion::factory()->create();

        $this->get(route('aspirantes.evaluacion.create', $evaluacion->aspirante))
            ->assertRedirect(route('aspirantes.evaluacion.edit', $evaluacion->aspirante));
    }

    public function test_no_permite_una_segunda_evaluacion(): void
    {
        // Preparar: un aspirante que ya tiene evaluación
        $evaluacion = Evaluacion::factory()->create();

        // Actuar: intentar evaluarlo otra vez
        $this->post(route('aspirantes.evaluacion.store', $evaluacion->aspirante), $this->datosValidos())
            ->assertRedirect(route('aspirantes.evaluacion.edit', $evaluacion->aspirante))
            ->assertSessionHas('aviso');

        // Verificar: sigue habiendo una sola evaluación
        $this->assertDatabaseCount('evaluaciones', 1);
    }

    public function test_edita_una_evaluacion_existente(): void
    {
        // Preparar
        $evaluacion = Evaluacion::factory()->create(['referencias' => 5]);
        $aspirante = $evaluacion->aspirante;

        // El formulario de edición trae el valor actual
        $this->get(route('aspirantes.evaluacion.edit', $aspirante))
            ->assertOk()
            ->assertSee('5.00');

        // Actuar
        $this->put(route('aspirantes.evaluacion.update', $aspirante), $this->datosValidos(['referencias' => '9.25']))
            ->assertRedirect(route('aspirantes.show', $aspirante))
            ->assertSessionHas('exito');

        // Verificar: fresh() vuelve a leer el registro desde la base
        $this->assertEquals('9.25', $evaluacion->fresh()->referencias);
    }

    public function test_rechaza_una_edicion_fuera_de_rango(): void
    {
        $evaluacion = Evaluacion::factory()->create(['referencias' => 5]);

        $this->put(route('aspirantes.evaluacion.update', $evaluacion->aspirante), $this->datosValidos(['referencias' => '11']))
            ->assertSessionHasErrors('referencias');

        // El valor original no cambió
        $this->assertEquals('5.00', $evaluacion->fresh()->referencias);
    }

    public function test_editar_sin_evaluacion_redirige_a_crear(): void
    {
        $aspirante = Aspirante::factory()->create();

        $this->get(route('aspirantes.evaluacion.edit', $aspirante))
            ->assertRedirect(route('aspirantes.evaluacion.create', $aspirante));
    }

    public function test_el_promedio_es_el_promedio_simple_de_las_tres_calificaciones(): void
    {
        // make() crea el modelo en memoria, sin guardarlo
        $evaluacion = Evaluacion::factory()->make([
            'conocimientos_tecnicos' => 9.5,
            'referencias' => 8.75,
            'habilidades_blandas' => 9,
        ]);

        $this->assertSame(9.08, $evaluacion->promedio);
    }
}
