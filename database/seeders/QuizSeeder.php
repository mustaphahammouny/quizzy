<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::role('teacher')->first();

        Quiz::factory(50)->create([
            'user_id' => $teacher->id,
        ]);
    }
}
