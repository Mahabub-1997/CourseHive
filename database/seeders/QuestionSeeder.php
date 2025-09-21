<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes = Quiz::all();

        if ($quizzes->isEmpty()) {
            $this->command->warn('⚠️ No quizzes found! Seed quizzes first.');
            return;
        }

        $questionsData = [
            ['question_text' => 'What is the main purpose of this lesson?'],
            ['question_text' => 'Which step comes first in the process?'],
            ['question_text' => 'Identify the key concepts covered in this part.'],
            ['question_text' => 'Explain the differences between these two methods.'],
            ['question_text' => 'What would happen if a step is skipped?'],
        ];

        foreach ($questionsData as $data) {
            Question::create([
                'quiz_id'       => $quizzes->random()->id, // assign random quiz
                'question_text' => $data['question_text'],
            ]);
        }
    }
}
