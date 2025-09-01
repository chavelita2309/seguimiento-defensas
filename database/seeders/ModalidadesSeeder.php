<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModalidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $modalidades = [
            // Nivel Licenciatura
            ['nombre' => 'Tesis', 'nivel' => 'licenciatura'],
            ['nombre' => 'Proyecto de Grado', 'nivel' => 'licenciatura'],
            ['nombre' => 'Trabajo Dirigido', 'nivel' => 'licenciatura'],
            ['nombre' => 'Graduación por Excelencia Académica', 'nivel' => 'licenciatura'],
           
            // Nivel Técnico Superior
            ['nombre' => 'Tesina', 'nivel' => 'tecnico_superior'],
            ['nombre' => 'Proyecto de Grado Técnico', 'nivel' => 'tecnico_superior'],
            ['nombre' => 'Pasantía', 'nivel' => 'tecnico_superior'],          
        ];

        DB::table('modalidades')->insert($modalidades);
    }
}
