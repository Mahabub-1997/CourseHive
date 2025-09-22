<?php

namespace App\Http\Controllers\Web\Backend\QuizResult;

use App\Http\Controllers\Controller; // <-- must extend THIS
use App\Models\OnlineCourse;
use App\Models\Part;
use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class QuizResultController extends Controller
{
    public function start($partId)
    {
        $part = Part::with('quiz.questions.options')->findOrFail($partId);

        if (!$part->quiz) {
            return redirect()->back()->with('error', 'No quiz found for this part.');
        }

        $quiz = $part->quiz;

        return view('backend.layouts.mycourse.start-quiz', compact('part', 'quiz'));
    }

    // Show course content with lessons, parts, and quiz panel
    public function showCourseQuiz($courseId)
    {
        $course = OnlineCourse::with('lessons.parts.quiz')->findOrFail($courseId);

        $lessons = $course->lessons;
        $totalParts = $course->lessons->pluck('parts')->flatten()->count();
        $completedParts = 0; // You can calculate this based on user progress

        $currentPart = null;
        $quiz = null;

        return view('backend.layouts.mycourse.quiz', compact(
            'course',
            'lessons',
            'totalParts',
            'completedParts',
            'currentPart',
            'quiz'
        ));
    }

    public function quizsubmit(Request $request, $quizId)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($quizId);
        $userId = auth()->id();

        $answers = $request->input('answers', []); // submitted answers
        $score   = 0;
        $results = [];

        foreach ($quiz->questions as $question) {
            $correctOption = $question->options->where('is_correct', 1)->first();
            $userAnswerId  = $answers[$question->id] ?? null;
            $userOption    = $userAnswerId ? $question->options->where('id', $userAnswerId)->first() : null;
            $isCorrect     = $correctOption && $userAnswerId == $correctOption->id;

            if ($isCorrect) {
                $score++;
            }

            $results[] = [
                'question_id'    => $question->id,
                'question'       => $question->question_text,
                'correct_answer' => $correctOption ? $correctOption->option_text : null,
                'user_answer'    => $userOption ? $userOption->option_text : null,
                'is_correct'     => $isCorrect,
            ];
        }

        $totalQuestions = $quiz->questions->count();
        $percentage     = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0;
        $isPassed       = $percentage >= 70;

        // Save result
        QuizResult::create([
            'quiz_id'         => $quiz->id,
            'user_id'         => $userId,
            'score'           => $score,
            'total_questions' => $totalQuestions,
            'percentage'      => $percentage,
            'is_passed'       => $isPassed,
            'answers'         => json_encode($results),
        ]);

        return view('backend.layouts.quiz_results.result', compact(
            'quiz',
            'results',
            'score',
            'totalQuestions',
            'percentage',
            'isPassed'
        ));
    }


    // Review answers before final submit
    public function review(Request $request, $quizId)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($quizId);
        $answers = $request->input('answers', []);

        $results = [];
        $score = 0;

        foreach ($quiz->questions as $question) {
            $correctOption = $question->options->where('is_correct', 1)->first();
            $userAnswerId = $answers[$question->id] ?? null;
            $userOption   = $userAnswerId ? $question->options->where('id', $userAnswerId)->first() : null;
            $isCorrect    = $correctOption && $userAnswerId == $correctOption->id;

            if ($isCorrect) $score++;

            $results[] = [
                'question_id'    => $question->id,
                'question'       => $question->question_text,
                'correct_answer' => $correctOption ? $this->optionText($correctOption) : null,
                'user_answer'    => $userOption ? $this->optionText($userOption) : null,
                'is_correct'     => $isCorrect,
            ];
        }

        $totalQuestions = $quiz->questions->count();
        $percentage     = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0;

        return view('backend.layouts.quiz_results.review', compact(
            'quiz',
            'answers',
            'results',
            'score',
            'totalQuestions',
            'percentage'
        ));
    }



    // Helper to normalize option text
    protected function optionText($option)
    {
        if (!$option) return null;
        $text = $option->option_text;

        if (is_array($text)) {
            return $text['text'] ?? json_encode($text);
        }

        $decoded = json_decode($text, true);
        if (json_last_error() === JSON_ERROR_NONE && $decoded) {
            return is_array($decoded) ? ($decoded['text'] ?? json_encode($decoded)) : (string)$decoded;
        }

        return (string)$text;
    }

    public function result($quizId)
    {
        $userId = auth()->id();

        // Load the latest result for this user & quiz
        $latestResult = QuizResult::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->latest()
            ->first();

        if (!$latestResult) {
            return redirect()->route('quiz.start', $quizId)
                ->with('error', 'No result found for this quiz.');
        }

        // Load quiz with questions & options
        $quiz = Quiz::with('questions.options')->findOrFail($quizId);

        // Decode stored answers (if stored as JSON)
        $results = $latestResult->answers;
        if (is_string($results)) {
            $results = json_decode($results, true);
        }

        // Make sure attemptNumber is defined
        $attemptNumber = $latestResult->attempt_number ?? 1;

        return view('backend.layouts.quiz_results.result', [
            'quiz'           => $quiz,
            'score'          => $latestResult->score,
            'percentage'     => $latestResult->percentage,
            'totalQuestions' => $latestResult->total_questions,
            'isPassed'       => $latestResult->is_passed,
            'attemptNumber'  => $attemptNumber,
            'results'        => $results,
        ]);
    }

}
