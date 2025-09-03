<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Dev User',
            'email' => 'dev@heygumball.com',
            'password' => Hash::make('dev'),
        ]);

        // Seed the Tattoo CRM system
        $this->call([
            TattooCrmSeeder::class,
        ]);
    }
}
