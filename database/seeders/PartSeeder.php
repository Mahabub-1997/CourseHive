<?php

namespace Database\Seeders;

use App\Models\Lesson;
use App\Models\Part;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessons = Lesson::all();

        if ($lessons->isEmpty()) {
            $this->command->warn('⚠️ No lessons found! Seed lessons first.');
            return;
        }

        $parts = [
            [
                'title' => 'Part 1: Introduction',
                'video' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            ],
            [
                'title' => 'Part 2: Basics',
                'video' => 'https://vimeo.com/76979871',
            ],
            [
                'title' => 'Part 3: Advanced Topics',
                'video' => 'videos/advanced_lesson.mp4', // example uploaded file
            ],
            [
                'title' => 'Part 4: Summary',
                'video' => null,
            ],
        ];

        foreach ($parts as $partData) {
            Part::create([
                'lesson_id' => $lessons->random()->id, // assign random lesson
                'title'     => $partData['title'],
                'video'     => $partData['video'],
            ]);
        }
    }
}
