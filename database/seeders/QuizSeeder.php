<?php

namespace Database\Seeders;

use App\Models\Part;
use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $parts = Part::all();

        if ($parts->isEmpty()) {
            $this->command->warn('⚠️ No parts found! Seed parts first.');
            return;
        }

        $quizzes = [
            ['title' => 'Quiz 1: Basics'],
            ['title' => 'Quiz 2: Intermediate'],
            ['title' => 'Quiz 3: Advanced'],
            ['title' => 'Quiz 4: Final Assessment'],
        ];

        foreach ($quizzes as $quizData) {
            Quiz::create([
                'part_id' => $parts->random()->id, // assign random part
                'title'   => $quizData['title'],
            ]);
        }
    }
}
