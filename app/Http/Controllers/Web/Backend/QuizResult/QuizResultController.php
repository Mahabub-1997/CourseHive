<?php

namespace App\Http\Controllers\Web\Backend\QuizResult;

use App\Http\Controllers\Controller; // <-- must extend THIS
use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class QuizResultController extends Controller
{


    // Show quiz (form)
//    public function start($quizId)
//    {
//        $quiz = Quiz::with('questions.options')->findOrFail($quizId);
//        return view('backend.layouts.quiz_results.start', compact('quiz'));
//    }

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

    // Final submit
//    public function submit(Request $request, $quizId)
//    {
//        $quiz    = Quiz::with('questions.options')->findOrFail($quizId);
//        $answers = $request->input('answers', []);
//
//        // ✅ consistent user id
//        $userId = auth()->id();
//
//        // Attempt count check
//        $attempts = QuizResult::where('quiz_id', $quizId)
//            ->where('user_id', $userId)
//            ->count();
//
//        if ($attempts >= 3) {
//            return redirect()->route('quiz.start', $quizId)
//                ->with('error', 'You have reached the maximum number of attempts (3).');
//        }
//
//        $score   = 0;
//        $results = [];
//
//        foreach ($quiz->questions as $question) {
//            $correctOption = $question->options->where('is_correct', 1)->first();
//            $userAnswerId  = $answers[$question->id] ?? null;
//            $userOption    = $userAnswerId ? $question->options->where('id', $userAnswerId)->first() : null;
//            $isCorrect     = $correctOption && $userAnswerId == $correctOption->id;
//
//            if ($isCorrect) $score++;
//
//            $results[] = [
//                'question_id'    => $question->id,
//                'question'       => $question->question_text,
//                'correct_answer' => $correctOption ? $this->optionText($correctOption) : null,
//                'user_answer'    => $userOption ? $this->optionText($userOption) : null,
//                'is_correct'     => $isCorrect,
//            ];
//        }
//
//        $totalQuestions = $quiz->questions->count();
//        $percentage     = $totalQuestions > 0 ? round(($score / $totalQuestions) * 100, 2) : 0;
//        $isPassed       = $percentage >= 70;
//        $attemptNumber  = $attempts + 1;
//
//        QuizResult::create([
//            'quiz_id'         => $quiz->id,
//            'user_id'         => $userId,  // ✅ saved correctly
//            'score'           => $score,
//            'total_questions' => $totalQuestions,
//            'percentage'      => $percentage,
//            'is_passed'       => $isPassed,
//            'answers'         => $results,
//            'attempt_number'  => $attemptNumber,
//        ]);
//
//        return view('backend.layouts.quiz_results.result', compact(
//            'quiz',
//            'score',
//            'percentage',
//            'results',
//            'totalQuestions',
//            'isPassed',
//            'attemptNumber'
//        ));
//    }


    public function submit(Request $request, $quizId)
    {
        $quiz   = Quiz::with('questions.options')->findOrFail($quizId);
        $answers = $request->input('answers', []);
        $userId  = auth()->id();

        // Attempt limit check
        $attempts = QuizResult::where('quiz_id', $quizId)->where('user_id', $userId)->count();
        if ($attempts >= 3) {
            return redirect()->route('quiz.start', $quizId)
                ->with('error', 'You have reached the maximum number of attempts (3).');
        }

        $score   = 0;
        $results = [];

        foreach ($quiz->questions as $question) {
            $correctOption = $question->options->where('is_correct', 1)->first();
            $userAnswerId  = $answers[$question->id] ?? null;
            $userOption    = $userAnswerId ? $question->options->where('id', $userAnswerId)->first() : null;
            $isCorrect     = $correctOption && $userAnswerId == $correctOption->id;

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
        $isPassed       = $percentage >= 70;
        $attemptNumber  = $attempts + 1;

        QuizResult::create([
            'quiz_id'        => $quiz->id,
            'user_id'        => $userId,
            'score'          => $score,
            'total_questions'=> $totalQuestions,
            'percentage'     => $percentage,
            'is_passed'      => $isPassed,
            'answers'        => $results,
            'attempt_number' => $attemptNumber,
        ]);

        return view('backend.layouts.quiz_results.result', compact(
            'quiz',
            'score',
            'percentage',
            'results',
            'totalQuestions',
            'isPassed',
            'attemptNumber'
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

        // Load latest result for this user & quiz
        $latestResult = QuizResult::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->latest()
            ->first();

        if (!$latestResult) {
            return redirect()->route('quiz.start', $quizId)
                ->with('error', 'No result found for this quiz.');
        }

        $quiz = Quiz::with('questions.options')->findOrFail($quizId);

        return view('backend.layouts.quiz_results.result', [
            'quiz'           => $quiz,
            'score'          => $latestResult->score,
            'percentage'     => $latestResult->percentage,
            'totalQuestions' => $latestResult->total_questions,
            'isPassed'       => $latestResult->is_passed,
            'attemptNumber'  => $latestResult->attempt_number,
            'results'        => $latestResult->answers, // JSON, decode in Blade
        ]);
    }

}
