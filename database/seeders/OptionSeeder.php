<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions = Question::all();

        if ($questions->isEmpty()) {
            $this->command->warn('⚠️ No questions found! Seed questions first.');
            return;
        }

        foreach ($questions as $question) {
            // Generate 4 options per question
            for ($i = 1; $i <= 4; $i++) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => "Option {$i} for Question #{$question->id}",
                    'is_correct'  => $i === 1, // make the first option correct
                ]);
            }
        }
    }
}
