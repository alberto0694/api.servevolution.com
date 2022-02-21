<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Usuario::create([
            'name' => 'Usuário de teste',
            'email' => 'usuario@teste.com.br',
            'password' => bcrypt('123456')
        ]);
    }
}
