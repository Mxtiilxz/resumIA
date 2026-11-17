<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// migracion: crea la tabla resumenes, donde se guarda cada resumen hecho con la ia
return new class extends Migration
{
    // up: lo que se hace al correr php artisan migrate
    public function up(): void
    {
        Schema::create('resumenes', function (Blueprint $table) {
            // id autoincremental
            $table->id();

            // el dueno del resumen
            // si se borra el usuario, tambien se borran sus resumenes
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // titulo que escribe el usuario
            $table->string('titulo');

            // el texto que pego el usuario (text porque puede ser largo)
            $table->text('texto_original');

            // modo: resumen, desglose o puntos
            $table->string('modo');

            // extension: corto, medio o largo
            $table->string('extension');

            // lo que respondio la ia
            $table->text('resultado');

            // created_at y updated_at
            $table->timestamps();
        });
    }

    // down: deshace lo de up (php artisan migrate:rollback)
    public function down(): void
    {
        Schema::dropIfExists('resumenes');
    }
};
