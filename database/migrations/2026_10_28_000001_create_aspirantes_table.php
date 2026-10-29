<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * up() crea la tabla; se ejecuta con `php artisan migrate`.
     */
    public function up(): void
    {
        Schema::create('aspirantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 150);
            $table->string('puesto', 150);
            // Se guarda el valor del enum EstadoCivil (ej. "union_libre").
            $table->string('estado_civil', 20);
            $table->date('fecha_nacimiento');
            $table->string('carrera', 150);
            $table->timestamps(); // created_at y updated_at
        });
    }

    /**
     * down() deshace lo de up(); se ejecuta con `php artisan migrate:rollback`.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirantes');
    }
};
