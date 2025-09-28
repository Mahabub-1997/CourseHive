<?php

namespace App\Http\Controllers\API\MyCourse;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\OnlineCourse;
use App\Models\Quiz;
use App\Models\QuizResult;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * 📌 1. Get basic course stats for logged-in user
     * - total enrolled courses
     * - in-progress count
     * - completed count
     * - paginated in-progress course details
     */
    public function index()
    {
        $userId = auth()->id();

        // Count totals
        $totalCourses = Enrollment::where('user_id', $userId)->count();
        $inProgress   = Enrollment::where('user_id', $userId)->where('status', 'pending')->count();
        $completed    = Enrollment::where('user_id', $userId)->where('status', 'success')->count();

        // Fetch in-progress courses with relations (lessons + parts)
        $inProgressCourses = Enrollment::with('course.lessons.parts')
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->paginate(6);

       $inProgressCourses->getCollection()->transform(function ($enrollment) {
    if ($enrollment->course && $enrollment->course->image) {
        $enrollment->course->image = 'uploads/courses/' . $enrollment->course->image;
    }
    return $enrollment;
});

        return response()->json([
            'status'  => true,
            'message' => 'My courses retrieved successfully',
            'data'    => [
                'totalCourses'      => $totalCourses,
                'inProgressCount'   => $inProgress,
                'completedCount'    => $completed,
                'inProgressCourses' => $inProgressCourses,
            ]
        ]);
    }

    /**
     * 📌 2. Dashboard data
     * - enrollment stats (same as index)
     * - enrolled courses with reviews, ratings, instructors
     */
    public function courseindex()
    {
        $userId = auth()->id();

        // Stats
        $totalCourses = Enrollment::where('user_id', $userId)->count();
        $inProgress   = Enrollment::where('user_id', $userId)->where('status', 'pending')->count();
        $completed    = Enrollment::where('user_id', $userId)->where('status', 'success')->count();

        $inProgressCourses = Enrollment::with('course.lessons.parts')
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->paginate(6);

        // Enrolled courses with relations
        $courses = Enrollment::with([
            'course.learns',
            'course.reviews.user',
            'course.instructors.user',
            'course.instructors.ratings',
        ])
            ->where('user_id', $userId)
            ->get()
            ->pluck('course');

        return response()->json([
            'status'  => true,
            'message' => 'Dashboard data retrieved successfully',
            'data'    => [
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

    /**
     * 📌 3. Show a specific enrolled course details
     */
    public function courseShow($courseId)
    {
        $userId = auth()->id();

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
                'status'  => false,
                'message' => 'Course not found or not enrolled',
            ], 404);
        }



        return response()->json([
            'status'  => true,
            'message' => 'Course data retrieved successfully',
            'data'    => $enrollment->course
        ]);
    }

    /**
     * 📌 4. Course content with lessons & parts
     * - supports navigation (previous/next part)
     */
    public function contentshow($id, $partId = null)
    {
        $userId = auth()->id();

        $course  = OnlineCourse::with(['lessons.parts', 'creator'])->findOrFail($id);
        $lessons = $course->lessons;

        // Flatten all parts into one collection
        $allParts = $lessons->pluck('parts')->flatten(1)->values();

        if ($allParts->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'No course parts found.'
            ], 404);
        }

        // Get current part (or first by default)
        $currentPart = $partId ? $allParts->firstWhere('id', $partId) : $allParts->first();

        // Find previous & next part
        $currentIndex  = $allParts->search(fn($p) => $p->id === $currentPart->id);
        $previousPart  = $allParts->get($currentIndex - 1);
        $nextPart      = $allParts->get($currentIndex + 1);

        return response()->json([
            'status' => true,
            'message' => 'Course content retrieved successfully',
            'data' => [
                'course'         => $course,
                'lessons'        => $lessons,
                'totalParts'     => $allParts->count(),
                'completedParts' => 0, // TODO: compute from user progress
                'currentPart'    => $currentPart,
                'previousPart'   => $previousPart,
                'nextPart'       => $nextPart,
            ]
        ]);
    }

    /**
     * 📌 5. Get course quiz with lessons & parts
     */
    public function getCourseQuiz($courseId)
    {
        $course = OnlineCourse::with('lessons.parts.quiz.questions.options')->find($courseId);

        if (!$course) {
            return response()->json([
                'status'  => false,
                'message' => "No course found with ID {$courseId}"
            ], 404);
        }

        $totalParts     = $course->lessons->pluck('parts')->flatten()->count();
        $completedParts = 0; // TODO: compute dynamically

        // Transform data into clean structure
        $data = [
            'id'              => $course->id,
            'title'           => $course->title,
            'completed_parts' => $completedParts,
            'total_parts'     => $totalParts,
            'lessons'         => $course->lessons->map(function ($lesson) {
                return [
                    'id'    => $lesson->id,
                    'title' => $lesson->title,
                    'parts' => $lesson->parts->map(function ($part) {
                        return [
                            'id'       => $part->id,
                            'title'    => $part->title,
                            'duration' => $part->duration,
                            'video'    => $part->video,
                            'quiz'     => $part->quiz ? [
                                'id'       => $part->quiz->id,
                                'title'    => $part->quiz->title,
                                'questions'=> $part->quiz->questions->map(function ($question) {
                                    return [
                                        'id'            => $question->id,
                                        'quiz_id'       => $question->quiz_id,
                                        'question_text' => $question->question_text,
                                        'options'       => $question->options->map(function ($option) {
                                            return [
                                                'id'          => $option->id,
                                                'question_id' => $option->question_id,
                                                'option_text' => $option->option_text,
                                                'is_correct'  => $option->is_correct,
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
}
