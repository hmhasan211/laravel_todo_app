<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $int= mt_rand(1262055681,1262055681);
        $string = date("Y-m-d H:i:s",$int);

        for ($i=1; $i<=10; $i++){
            Task::query()->create([
                'name' => fake()->text(50),
                'description' =>  fake()->text(200),
                'todo_id' => random_int(1, 10),
                'start_date'=> date("Y-m-d H:i:s",$int),
                'end_date'=> date("Y-m-d H:i:s",$int),
            ]);
        }
    }
}
