<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $student = User::factory()->create([
            'first_name' => 'Student',
            'last_name' => 'Student',
            'email' => 'student@quizzy.com',
        ]);

        $teacher = User::factory()->create([
            'first_name' => 'Teacher',
            'last_name' => 'Teacher',
            'email' => 'teacher@quizzy.com',
        ]);

        $student->assignRole('student');

        $teacher->assignRole('teacher');
    }
}
