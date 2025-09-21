<?php

namespace App\Http\Controllers\API\Certificate;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class CertificateController extends Controller


{
    /**
     * Get all enrollments with certificate info.
     */
    /**
     * Get all enrollments with certificate info
     */
    public function index(Request $request)
    {
        // Load all enrollments with related user, course, instructors
        $enrollments = Enrollment::with(['user', 'course', 'course.instructors'])
            ->latest()
            ->get();

        // First, calculate duration_hours for each enrollment's course
        $enrollments->transform(function ($enrollment) {
            $duration = $enrollment->course->duration ?? null;
            $hours = 0;

            if ($duration) {
                if (str_contains($duration, 'hour')) {
                    $hours = (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);
                } elseif (str_contains($duration, 'minute')) {
                    $hours = ((int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT)) / 60;
                }
            }

            // Attach duration_hours to course for later sum
            if ($enrollment->course) {
                $enrollment->course->duration_hours = $hours;
            }

            return $enrollment;
        });

        // Prepare the API response data
        $data = $enrollments->map(function ($enrollment) {
            return [
                'enrollment_id' => $enrollment->id,
                'status' => $enrollment->status,
                'progress' => $enrollment->progress ?? 0,
                'estimated_completion' => $enrollment->estimated_completion,
                'enrolled_at' => $enrollment->enrolled_at ?? $enrollment->created_at,
                'course' => [
                    'id' => $enrollment->course->id ?? null,
                    'title' => $enrollment->course->title ?? null,
                    'duration' => $enrollment->course->duration ?? null,
                    'duration_hours' => $enrollment->course->duration_hours ?? 0,
                    'instructor' => $enrollment->course->instructors->name ?? null,
                ],
                'user' => [
                    'id' => $enrollment->user->id ?? null,
                    'name' => $enrollment->user->name ?? null,
                    'email' => $enrollment->user->email ?? null,
                ],
            ];
        });

        // Calculate totals
        $total_training_hours = $enrollments
            ->where('status', 'success') // only completed courses
            ->sum(fn($enrollment) => $enrollment->course ? $enrollment->course->duration_hours ?? 0 : 0);

        $total_completed = $enrollments->where('status', 'success')->count();
        $total_in_progress = $enrollments->where('status', 'pending')->count();

        return response()->json([
            'success' => true,
            'data' => $data,
            'total_enrollments' => $enrollments->count(),
            'total_completed' => $total_completed,
            'total_in_progress' => $total_in_progress,
            'total_training_hours' => $total_training_hours,
        ]);
    }


    /**
     * Show single enrollment certificate info
     */
    public function show($id)
    {
        $enrollment = Enrollment::with(['user', 'course', 'course.instructors'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'enrollment_id' => $enrollment->id,
                'status' => $enrollment->status,
                'progress' => $enrollment->progress ?? 0,
                'estimated_completion' => $enrollment->estimated_completion,
                'enrolled_at' => $enrollment->enrolled_at ?? $enrollment->created_at,
                'course' => [
                    'id' => $enrollment->course->id ?? null,
                    'title' => $enrollment->course->title ?? null,
                    'duration' => $enrollment->course->duration ?? null,
                    'instructor' => $enrollment->course->instructors->name ?? null,
                ],
                'user' => [
                    'id' => $enrollment->user->id ?? null,
                    'name' => $enrollment->user->name ?? null,
                    'email' => $enrollment->user->email ?? null,
                ],
            ]
        ]);
    }
}
