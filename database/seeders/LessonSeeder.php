<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\OnlineCourse;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = OnlineCourse::all();

        if ($courses->isEmpty()) {
            $this->command->warn('⚠️ No courses found! Seed courses first.');
            return;
        }

        $lessons = [
            [
                'title'       => 'Introduction to Course',
                'description' => 'This lesson introduces the main concepts of the course.',
            ],
            [
                'title'       => 'Lesson 1: Basics',
                'description' => 'Covers the fundamental concepts and basic exercises.',
            ],
            [
                'title'       => 'Lesson 2: Intermediate',
                'description' => 'Dive deeper into intermediate concepts with examples.',
            ],
            [
                'title'       => 'Lesson 3: Advanced',
                'description' => 'Advanced topics and practical exercises for mastery.',
            ],
        ];

        foreach ($lessons as $lessonData) {
            Lesson::create([
                'course_id'   => $courses->random()->id, // assign random course
                'title'       => $lessonData['title'],
                'description' => $lessonData['description'],
            ]);
        }
    }
}
