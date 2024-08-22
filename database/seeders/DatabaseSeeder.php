<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Visit;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        # Crear 10 usuarios aleatorios
        User::factory(10)->create();
        # Crear 500 visitas aleatorias
        Visit::factory(500)->create();

    }
}
