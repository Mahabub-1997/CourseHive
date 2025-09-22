<?php

namespace App\Http\Controllers\API\QuizOverview;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourse;
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

}
