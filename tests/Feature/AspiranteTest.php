<?php

namespace Tests\Feature;

use App\Enums\EstadoCivil;
use App\Models\Aspirante;
use App\Models\Evaluacion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AspiranteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Formulario válido; cada prueba cambia solo el campo que quiere probar.
     */
    private function datosValidos(array $cambios = []): array
    {
        return array_merge([
            'nombre_completo' => 'María Fernanda López Ruiz',
            'puesto' => 'Desarrolladora Full Stack',
            'estado_civil' => EstadoCivil::Soltero->value,
            'fecha_nacimiento' => '1995-04-12',
            'carrera' => 'Ingeniería en Sistemas',
        ], $cambios);
    }

    public function test_la_raiz_redirige_al_listado(): void
    {
        $this->get('/')->assertRedirect('/aspirantes');
    }

    public function test_muestra_el_formulario_de_alta(): void
    {
        $this->get(route('aspirantes.create'))
            ->assertOk()
            ->assertSee('Registrar aspirante');
    }

    public function test_registra_un_aspirante_valido(): void
    {
        // Actuar
        $response = $this->post(route('aspirantes.store'), $this->datosValidos());

        // Verificar: redirige al detalle y se guardó en la base
        $aspirante = Aspirante::first();
        $response->assertRedirect(route('aspirantes.show', $aspirante))
            ->assertSessionHas('exito');
        $this->assertDatabaseHas('aspirantes', [
            'nombre_completo' => 'María Fernanda López Ruiz',
            'estado_civil' => 'soltero',
        ]);
    }

    public function test_rechaza_el_alta_con_campos_vacios(): void
    {
        $this->post(route('aspirantes.store'), [])
            ->assertSessionHasErrors(['nombre_completo', 'puesto', 'estado_civil', 'fecha_nacimiento', 'carrera']);

        // No se guardó nada
        $this->assertDatabaseCount('aspirantes', 0);
    }

    public function test_rechaza_un_estado_civil_fuera_del_catalogo(): void
    {
        $this->post(route('aspirantes.store'), $this->datosValidos(['estado_civil' => 'kasado']))
            ->assertSessionHasErrors('estado_civil');

        $this->assertDatabaseCount('aspirantes', 0);
    }

    public function test_rechaza_una_fecha_de_nacimiento_que_no_sea_anterior_a_hoy(): void
    {
        $this->post(route('aspirantes.store'), $this->datosValidos(['fecha_nacimiento' => now()->toDateString()]))
            ->assertSessionHasErrors(['fecha_nacimiento' => 'La fecha de nacimiento debe ser anterior a hoy.']);
    }

    public function test_rechaza_textos_de_mas_de_150_caracteres(): void
    {
        $this->post(route('aspirantes.store'), $this->datosValidos(['nombre_completo' => str_repeat('a', 151)]))
            ->assertSessionHasErrors('nombre_completo');
    }

    public function test_el_listado_muestra_aspirantes_y_su_promedio(): void
    {
        // Preparar: un aspirante evaluado (promedio 8) y otro sin evaluar
        $evaluado = Aspirante::factory()->create(['nombre_completo' => 'Ana Evaluada']);
        Evaluacion::factory()->for($evaluado)->create([
            'conocimientos_tecnicos' => 9,
            'referencias' => 8,
            'habilidades_blandas' => 7,
        ]);
        Aspirante::factory()->create(['nombre_completo' => 'Beto Pendiente']);

        // Actuar y verificar
        $this->get(route('aspirantes.index'))
            ->assertOk()
            ->assertSee('Ana Evaluada')
            ->assertSee('8.00')
            ->assertSee('Beto Pendiente')
            ->assertSee('Sin evaluar');
    }

    public function test_el_listado_filtra_por_nombre_o_puesto(): void
    {
        // Preparar
        Aspirante::factory()->create(['nombre_completo' => 'Carla Pérez', 'puesto' => 'Contadora']);
        Aspirante::factory()->create(['nombre_completo' => 'Diego Ruiz', 'puesto' => 'Desarrollador Backend']);

        // Búsqueda por nombre
        $this->get(route('aspirantes.index', ['buscar' => 'Carla']))
            ->assertSee('Carla Pérez')
            ->assertDontSee('Diego Ruiz');

        // Búsqueda por puesto, sin distinguir mayúsculas
        $this->get(route('aspirantes.index', ['buscar' => 'backend']))
            ->assertSee('Diego Ruiz')
            ->assertDontSee('Carla Pérez');
    }

    public function test_el_listado_esta_paginado(): void
    {
        Aspirante::factory()->count(15)->create();

        // Primera página: 10 de 15
        $this->get(route('aspirantes.index'))
            ->assertOk()
            ->assertViewHas('aspirantes', fn ($aspirantes) => $aspirantes->count() === 10 && $aspirantes->total() === 15);
    }

    public function test_el_detalle_muestra_datos_generales_y_evaluacion(): void
    {
        // Preparar: aspirante con evaluación (promedio 9.08)
        $aspirante = Aspirante::factory()->create([
            'nombre_completo' => 'Elena Torres',
            'estado_civil' => EstadoCivil::UnionLibre,
            'fecha_nacimiento' => '1990-09-03',
        ]);
        Evaluacion::factory()->for($aspirante)->create([
            'conocimientos_tecnicos' => 9.5,
            'referencias' => 8.75,
            'habilidades_blandas' => 9,
            'comentarios' => 'Excelente dominio de Laravel.',
        ]);

        // Actuar y verificar
        $this->get(route('aspirantes.show', $aspirante))
            ->assertOk()
            ->assertSee('Elena Torres')
            ->assertSee('Unión libre')
            ->assertSee('03/09/1990')
            ->assertSee('8.75')
            ->assertSee('9.08')
            ->assertSee('Excelente dominio de Laravel.')
            ->assertSee('Editar evaluación');
    }

    public function test_el_detalle_sin_evaluacion_ofrece_evaluar(): void
    {
        $aspirante = Aspirante::factory()->create();

        $this->get(route('aspirantes.show', $aspirante))
            ->assertOk()
            ->assertSee('Este aspirante aún no ha sido evaluado.')
            ->assertSee(route('aspirantes.evaluacion.create', $aspirante));
    }

    public function test_un_aspirante_inexistente_responde_404_en_espanol(): void
    {
        $this->get('/aspirantes/999')
            ->assertNotFound()
            ->assertSee('Página no encontrada');
    }

    public function test_escapa_el_html_ingresado_por_el_usuario(): void
    {
        $aspirante = Aspirante::factory()->create(['nombre_completo' => '<script>alert(1)</script>']);

        // false = comparar el HTML crudo; la etiqueta debe aparecer escapada, no ejecutable
        $this->get(route('aspirantes.show', $aspirante))
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }
}
