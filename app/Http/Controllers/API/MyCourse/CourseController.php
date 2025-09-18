<?php

namespace App\Http\Controllers\API\MyCourse;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Learn;
use App\Models\OnlineCourse;
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
}
