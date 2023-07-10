<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void{

        $this->call(RolesPermissionSeeder::class);

        \App\Models\User::factory()->create([
            'name' => 'Ankoos Shek',
            'email' => 'ashek@mcallinn.com',
            'password' => Hash::make('12345678'),
            'is_admin' => true
        ]);
    }
}
