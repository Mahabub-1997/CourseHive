<?php

namespace Database\Seeders;

use App\Models\Instructor;
use App\Models\OnlineCourse;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Make sure you already have some courses & users seeded
        $courses = OnlineCourse::all();
        $users   = User::all();

        if ($courses->isEmpty() || $users->isEmpty()) {
            $this->command->warn('⚠️ Please seed Courses and Users first!');
            return;
        }

        // Example seeding 10 instructors
        foreach (range(1, 10) as $i) {
            Instructor::create([
                'course_id'    => $courses->random()->id,
                'user_id'      => $users->random()->id,
                'name'         => fake()->name(),
                'image'        => null, // Or put a default like 'instructors/default.png'
                'rating'       => fake()->randomFloat(2, 3, 5), // between 3.00 - 5.00
                'total_lesson' => fake()->numberBetween(5, 50),
            ]);
        }
    }
}
