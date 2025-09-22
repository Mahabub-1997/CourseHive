<?php

namespace App\Http\Controllers\API\Enroll;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\OnlineCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function enroll($course_id)
    {
        $user = Auth::user();

        // 1️⃣ Find course
        $course = OnlineCourse::find($course_id);
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found.'
            ], 404);
        }

        // 2️⃣ Only free courses can be enrolled directly
        if ($course->course_type != 'free') {
            return response()->json([
                'status' => 'error',
                'message' => 'This is a paid course. Please buy it.'
            ], 403);
        }

        // 3️⃣ Check if already enrolled
        $existing = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->first();

        if ($existing) {
            return response()->json([
                'status' => 'success',
                'message' => 'You are already enrolled in this course.'
            ], 200);
        }

        // 4️⃣ Create enrollment
        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course_id,
            'status' => 'pending',
            'enrolled_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'You have successfully enrolled in this course!'
        ], 201);
    }
}
