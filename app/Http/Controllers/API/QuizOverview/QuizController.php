<?php

namespace App\Http\Controllers\API\QuizOverview;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourse;
use App\Models\Quiz;
use App\Models\QuizResult;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function performance(Request $request)
    {
        $userId = Auth::id();

        // Fetch all quiz results with quiz → part → course
        $quizResults = QuizResult::with('course.quiz')
            ->where('user_id', $userId)
            ->latest()
            ->get();

//dd($quizResults );

        $totalQuizzes = $quizResults->count();
        $passedQuizzes = $quizResults->where('is_passed', 1)->count();
        $averageScore = $quizResults->avg('score');

        // Quiz performance history
        $quizPerformanceHistory = $quizResults->map(function ($result) {
            return [
                'title' => $result->quiz->title ?? 'Untitled Quiz',

//                'course' => $result->quiz->course->title ?? 'N/A',
                'course' => $result->quiz->course_title,

                'status' => $result->is_passed ? 'Passed' : 'Failed',
                'best_score' => $result->score,
                'attempts' => $result->attempt_number ?? 1,
                'time' => $result->updated_at ?? 'N/A',
                'date' => $result->created_at->format('Y-m-d'),
            ];
        });

        // Performance by course
        $performanceByCourse = $quizResults->groupBy(function ($result) {
            return $result->quiz->course->id ?? 0;
        })->map(function ($group) {
            $courseTitle = $group->first()->quiz->course_title ?? 'N/A';



            $avgScore = round($group->avg('score'));
            $status = $avgScore >= 80 ? 'good' : ($avgScore >= 50 ? 'average' : 'poor');

            return [
                'course' => $courseTitle,
                'score' => $avgScore,
                'status' => $status,
            ];
        })->values(); // reset keys

        return response()->json([
            'status' => true,
            'message' => 'Quiz performance data fetched successfully',
            'data' => [
                'total_quizzes_taken' => $totalQuizzes,
                'pass_rate' => $totalQuizzes ? round(($passedQuizzes / $totalQuizzes) * 100) : 0,
                'average_score' => $averageScore ? round($averageScore) : 0,
                'quiz_performance_history' => $quizPerformanceHistory,
                'performance_by_course' => $performanceByCourse,
            ]
        ]);
    }


    /**
     * Submit quiz answers and calculate score
     */
    public function submit(Request $request, $quizId)
    {
        $quiz = Quiz::with('questions.options')->findOrFail($quizId);
        $userId = $request->user()->id;

        // Get submitted answers (question_id => option_id)
        $answers = $request->input('answers', []);

        $score = 0;
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
        $quizResult = QuizResult::create([
            'quiz_id'         => $quiz->id,
            'user_id'         => $userId,
            'score'           => $score,
            'total_questions' => $totalQuestions,
            'percentage'      => $percentage,
            'is_passed'       => $isPassed,
            'answers'         => json_encode($results),
        ]);

        // Return JSON response instead of view
        return response()->json([
            'status' => true,
            'message' => 'Quiz submitted successfully',
            'quiz' => [
                'id' => $quiz->id,
                'title' => $quiz->title,
            ],
            'score' => $score,
            'total_questions' => $totalQuestions,
            'percentage' => $percentage,
            'is_passed' => $isPassed,
            'results' => $results,
        ]);
    }


}
