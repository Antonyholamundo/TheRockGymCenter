<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categorias;

class CategoriasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Ropa Deportiva',
                'descripcion' => 'Ropa y accesorios deportivos para entrenamiento',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Bebidas',
                'descripcion' => 'Bebidas energéticas, isotónicas y agua',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Suplementos',
                'descripcion' => 'Suplementos nutricionales y proteínas',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Equipo Deportivo',
                'descripcion' => 'Equipos y accesorios para ejercicio',
                'estado' => 'Activo'
            ]
        ];

        foreach ($categorias as $categoria) {
            Categorias::create($categoria);
        }
    }
}