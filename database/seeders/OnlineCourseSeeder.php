<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OnlineCourse;
use Illuminate\Support\Str;

class OnlineCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Example: Creating 10 courses
        for ($i = 1; $i <= 10; $i++) {
            OnlineCourse::create([
                'title' => 'Course ' . $i,
                'description' => 'This is the description for Course ' . $i,
                'price' => rand(0, 1000), // random price
                'level' => ['Beginner', 'Intermediate', 'Advanced'][array_rand(['Beginner', 'Intermediate', 'Advanced'])],
                'duration' => rand(1, 12) . ' weeks',
                'language' => ['English', 'Bangla', 'Spanish'][array_rand(['English', 'Bangla', 'Spanish'])],
                'image' => 'courses/course_' . $i . '.png',
                'course_type' => ['free', 'paid'][array_rand(['free', 'paid'])],
                'user_id' => 1, // Assign a valid user_id from your users table
                'rating_id' => 1, // Assign a valid rating_id from your ratings table
                'category_id' => 1, // Assign a valid category_id
                'created_by' => 1,
                'updated_by' => 1,
            ]);
        }
    }
}
