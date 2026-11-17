<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// datos de prueba: dos usuarios con un resumen cada uno
// sirve para mostrar que cada usuario ve solo lo suyo
// se corre con: php artisan migrate:fresh --seed
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // usuario 1: ana (la contrasena se encripta sola por el cast 'hashed' del modelo user)
        $ana = User::create([
            'name' => 'Ana',
            'email' => 'ana@resumia.cl',
            'password' => 'resumia123',
        ]);

        // usuario 2: beto
        $beto = User::create([
            'name' => 'Beto',
            'email' => 'beto@resumia.cl',
            'password' => 'resumia123',
        ]);

        // resumen de ana, creado por la relacion (asi el user_id queda puesto solo)
        $ana->resumenes()->create([
            'titulo' => 'Lectura semana 5: Ingeniería de requisitos',
            'texto_original' => 'La ingeniería de requisitos es el proceso de descubrir, analizar, documentar y validar las necesidades que un sistema debe satisfacer.',
            'modo' => 'desglose',
            'extension' => 'medio',
            'resultado' => "Objetivo general\nComprender cómo identificar, priorizar y validar los requisitos de un sistema.\n\nPuntos principales\n• Diferencia entre requisitos funcionales y no funcionales.\n• Técnicas de levantamiento: entrevistas, encuestas y prototipos.\n\nConclusión\nUn buen levantamiento de requisitos reduce costos y errores.",
        ]);

        // resumen de beto
        $beto->resumenes()->create([
            'titulo' => 'Acta reunión de proyecto Laravel',
            'texto_original' => 'Se definió el alcance del MVP, el reparto de tareas entre los integrantes y las fechas de entrega de la unidad.',
            'modo' => 'puntos',
            'extension' => 'corto',
            'resultado' => "• Se definió el alcance del MVP.\n• Se repartieron las tareas.\n• Se fijaron las fechas de entrega.",
        ]);
    }
}
