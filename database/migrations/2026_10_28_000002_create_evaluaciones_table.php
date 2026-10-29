<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            // Llave foránea hacia aspirantes. unique() garantiza UNA evaluación por aspirante (1 a 1).
            // cascadeOnDelete(): si se borra el aspirante, su evaluación se borra también.
            $table->foreignId('aspirante_id')->unique()->constrained('aspirantes')->cascadeOnDelete();
            // decimal(4,2): 4 dígitos en total, 2 decimales -> de 0.00 a 99.99 (nosotros usamos 0 a 10).
            $table->decimal('conocimientos_tecnicos', 4, 2);
            $table->decimal('referencias', 4, 2);
            $table->decimal('habilidades_blandas', 4, 2);
            $table->text('comentarios')->nullable();
            $table->timestamps();
        });

        // Segunda línea de defensa: aunque la app ya valida, la BD también rechaza valores fuera de 0-10.
        // Solo en MySQL/MariaDB; SQLite (usado en las pruebas) no permite agregar CHECK con ALTER TABLE.
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb'])) {
            foreach (['conocimientos_tecnicos', 'referencias', 'habilidades_blandas'] as $columna) {
                DB::statement("ALTER TABLE evaluaciones ADD CONSTRAINT chk_{$columna} CHECK ({$columna} BETWEEN 0 AND 10)");
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
