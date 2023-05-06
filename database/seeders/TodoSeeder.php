<?php

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i=1; $i<=10; $i++){
            Todo::query()->create([
                'title' => fake()->text(150),
                'description' =>  fake()->text(250),
                'completed' => random_int(0, 1),
                'user_id' => random_int(1, 2),
            ]);
        }
    }
}
