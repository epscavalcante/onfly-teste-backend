<?php

namespace Database\Seeders;

use App\Models\Flight;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Usuário de teste 1',
            'email' => 'user1@example.com'
        ]);
        User::factory()->create([
            'name' => 'Usuário de teste 2',
            'email' => 'user2@example.com'
        ]);

        User::factory(10)
            ->has(Flight::factory()->requested()->count(rand(1, 3)))
            ->has(Flight::factory()->approved()->count(rand(3, 5)))
            ->has(Flight::factory()->canceled()->count(rand(1, 3)))
            ->create();
    }
}
