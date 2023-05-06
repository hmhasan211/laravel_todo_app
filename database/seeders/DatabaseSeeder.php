<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

       User::create([
            'name' => 'User One',
            'email' => 'userone@gmail.com',
            'password'=>Hash::make('admin123'),
       ]);
       User::create([
            'name' => 'User Two',
            'email' => 'usertwo@gmail.com',
            'password'=>Hash::make('admin123'),
        ]);

        $this->call([
            TodoSeeder::class,
            TaskSeeder::class
    ]);
    }
}
