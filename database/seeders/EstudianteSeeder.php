<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Estudiante;

class EstudianteSeeder extends Seeder
{
    public function run(): void
    {
        $estudiantes = [
            [
                'nombre' => 'Carlos Perez',
                'correo' => 'carlos.perez@correo.com',
                'programa' => 'Ingenieria Informatica',
            ],
            [
                'nombre' => 'Laura Gomez',
                'correo' => 'laura.gomez@correo.com',
                'programa' => 'Ingenieria de Sistemas',
            ],
            [
                'nombre' => 'Andres Rodriguez',
                'correo' => 'andres.rodriguez@correo.com',
                'programa' => 'Administracion de Empresas',
            ],
            [
                'nombre' => 'Sofia Martinez',
                'correo' => 'sofia.martinez@correo.com',
                'programa' => 'Derecho',
            ],
            [
                'nombre' => 'Mateo Vargas',
                'correo' => 'mateo.vargas@correo.com',
                'programa' => 'Contaduria Publica',
            ],
        ];

        foreach ($estudiantes as $estudiante) {
            Estudiante::create($estudiante);
        }
    }
}