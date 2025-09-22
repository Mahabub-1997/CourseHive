<?php

namespace App\Http\Controllers\API\MyCourse;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Learn;
use App\Models\OnlineCourse;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Rating;
use App\Models\Reviews;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    // 📌 Get all courses
    public function index()
    {
        $userId = auth()->id();
//        $userId = 2;

        // Total courses the user enrolled in
        $totalCourses = Enrollment::where('user_id', $userId)->count();

        // Count of in-progress courses
        $inProgress = Enrollment::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        // In-progress courses with pagination + course details
        $inProgressCourses = Enrollment::with('course.lessons.parts')
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->paginate(6);

        // Count of completed courses
        $inComplete = Enrollment::where('user_id', $userId)
            ->where('status', 'success')
            ->count();


        return response()->json([
            'status' => true,
            'message' => 'My courses retrieved successfully',
            'data' => [
                'totalCourses'      => $totalCourses,
                'inProgressCount'   => $inProgress,
                'completedCount'    => $inComplete,
                'inProgressCourses' => $inProgressCourses,
            ]
        ]);
    }


    public function courseindex()

    {
        $userId = auth()->id();

        // USER ENROLLMENT STATS
        $totalCourses = Enrollment::where('user_id', $userId)->count();

        $inProgress = Enrollment::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        $inProgressCourses = Enrollment::with('course.lessons.parts')
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->paginate(6);

        $completed = Enrollment::where('user_id', $userId)
            ->where('status', 'success')
            ->count();

        //  ONLY ENROLLED COURSES WITH RELATIONS
        $courses = Enrollment::with([
            'course.learns',
            'course.reviews.user',
            'course.instructors.user',
            'course.instructors.ratings',
        ])
            ->where('user_id', $userId)
            ->get()
            ->pluck('course'); // take only the course models

        // RESPONSE
        return response()->json([
            'status' => true,
            'message' => 'Dashboard data retrieved successfully',
            'data' => [
                'user_stats' => [
                    'totalCourses'      => $totalCourses,
                    'inProgressCount'   => $inProgress,
                    'completedCount'    => $completed,
                    'inProgressCourses' => $inProgressCourses,
                ],
                'enrolled_courses' => $courses
            ]
        ]);
    }
    public function courseShow($courseId)
    {
        $userId = auth()->id();

        // Find if user enrolled in this course
        $enrollment = Enrollment::with([
            'course.learns',
            'course.reviews.user',
            'course.instructors.user',
            'course.instructors.ratings',
            'course.lessons.parts.quiz.questions.options',



        ])
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (!$enrollment) {
            return response()->json([
                'status' => false,
                'message' => 'Course not found or not enrolled',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Course data retrieved successfully',
            'data' => $enrollment->course
        ]);
    }

    public function contentshow($id, $partId = null)
    {
        $userId = auth()->id();

        // Load course with lessons and parts
        $course = OnlineCourse::with(['lessons.parts', 'creator'])->findOrFail($id);

        $lessons = $course->lessons;

        // Flatten all parts into a single ordered collection
        $allParts = $lessons->pluck('parts')->flatten(1)->values();

        if ($allParts->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No course parts found.'
            ], 404);
        }

        // Find the requested part (or default to the first part)
        $currentPart = $partId ? $allParts->firstWhere('id', $partId) : null;
        $currentPart = $currentPart ?? $allParts->first();

        // Find index and prev/next
        $currentIndex = $allParts->search(fn($p) => $p->id === $currentPart->id);
        $previousPart = $allParts->get($currentIndex - 1);
        $nextPart     = $allParts->get($currentIndex + 1);

        $totalParts = $allParts->count();
        $completedParts = 0; // You can compute based on user progress later

        return response()->json([
            'status' => true,
            'message' => 'Course content retrieved successfully',
            'data' => [
                'course'         => $course,
                'lessons'        => $lessons,
                'totalParts'     => $totalParts,
                'completedParts' => $completedParts,
                'currentPart'    => $currentPart,
                'previousPart'   => $previousPart,
                'nextPart'       => $nextPart,
            ]
        ]);
    }
//    public function quiz($courseId)
//    {
//        $userId = auth()->id();
//
//        // Load course with lessons + parts
//        $course = OnlineCourse::with('lessons.parts')->findOrFail($courseId);
//
//        // All lessons for sidebar
//        $lessons = $course->lessons;
//
//        // Flatten all parts for progress calculation
//        $allParts = $lessons->pluck('parts')->flatten(1);
//
//        $totalParts = $allParts->count();
//        $completedParts = $allParts->where('is_completed', true)->count();
//
//        if ($allParts->isEmpty()) {
//            return response()->json([
//                'status' => false,
//                'message' => 'No parts found for this course.'
//            ], 404);
//        }
//
//        // Get current part (first part by default)
//        $currentPart = $allParts->first();
//
//        // Load quiz with questions + options
//        $quiz = Quiz::with('questions.options')
//            ->where('part_id', $currentPart->id)
//            ->first();
//
//        return response()->json([
//            'status' => true,
//            'message' => 'Quiz data retrieved successfully',
//            'data' => [
//                'course'         => $course,
//                'lessons'        => $lessons,
//                'currentPart'    => $currentPart,
//                'quiz'           => $quiz,
//                'totalParts'     => $totalParts,
//                'completedParts' => $completedParts,
//
//            ]
//        ]);
//    }
    // GET API to fetch course content with lessons, parts, and part-wise quizzes
// GET API to fetch course content with part-wise quizzes
    public function getCourseQuiz($courseId)
    {
        // Load course with lessons, parts, quizzes, questions, and options
        $course = OnlineCourse::with('lessons.parts.quiz.questions.options')->find($courseId);

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => "No course found with ID {$courseId}"
            ], 404);
        }

        $totalParts = $course->lessons->pluck('parts')->flatten()->count();
        $completedParts = 0; // You can calculate this dynamically per user

        $data = [
            'id' => $course->id,
            'title' => $course->title,
            'completed_parts' => $completedParts,
            'total_parts' => $totalParts,
            'lessons' => $course->lessons->map(function($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'parts' => $lesson->parts->map(function($part) {
                        return [
                            'id' => $part->id,
                            'title' => $part->title,
                            'duration' => $part->duration,
                            'video' => $part->video,
                            'quiz' => $part->quiz ? [
                                'id' => $part->quiz->id,
                                'title' => $part->quiz->title,
                                'questions' => $part->quiz->questions->map(function($question) {
                                    return [
                                        'id' => $question->id,
                                        'quiz_id' => $question->quiz_id,
                                        'question_text' => $question->question_text,
                                        'created_at' => $question->created_at,
                                        'updated_at' => $question->updated_at,
                                        'options' => $question->options->map(function($option) {
                                            return [
                                                'id' => $option->id,
                                                'question_id' => $option->question_id,
                                                'option_text' => $option->option_text, // keeps JSON format
                                                'is_correct' => $option->is_correct,
                                                'created_at' => $option->created_at,
                                                'updated_at' => $option->updated_at,
                                            ];
                                        }),
                                    ];
                                }),
                            ] : null,
                        ];
                    }),
                ];
            }),
        ];

        return response()->json([
            'status' => true,
            'course' => $data,
        ]);
    }

    //result

    public function getResult($quizId)
    {
        $userId = auth()->id();

        // Load the latest result for this user & quiz
        $latestResult = QuizResult::where('quiz_id', $quizId)
            ->where('user_id', $userId)
            ->latest()
            ->first();

        if (!$latestResult) {
            return response()->json([
                'status'  => false,
                'message' => 'No result found for this quiz.',
            ], 404);
        }

        // Load quiz with questions & options
        $quiz = Quiz::with('questions.options')->find($quizId);

        if (!$quiz) {
            return response()->json([
                'status'  => false,
                'message' => 'Quiz not found.',
            ], 404);
        }

        // Decode stored answers (if stored as JSON)
        $results = $latestResult->answers;
        if (is_string($results)) {
            $results = json_decode($results, true);
        }

        // Make sure attemptNumber is defined
        $attemptNumber = $latestResult->attempt_number ?? 1;

        return response()->json([
            'status'         => true,
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
