<?php

namespace App\Http\Controllers\Web\Backend\Certificate;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\OnlineCourse;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Str;

class CertificateController extends Controller
{

    /**
     * Display a list of enrollments.
     * Optionally filter by course.
     */
    public function index($courseId = null)
    {
        if ($courseId) {
            $course = OnlineCourse::findOrFail($courseId);
            $enrollments = Enrollment::with(['user', 'course'])
                ->where('course_id', $courseId)
                ->latest()
                ->get();
        } else {
            $course = null;
            $enrollments = Enrollment::with(['user', 'course'])
                ->latest()
                ->get();
        }

        // Total Training Hours (sum durations of completed courses only)
        $totalTrainingHours = $enrollments
            ->filter(fn($enrollment) => $enrollment->status === 'success') // only completed courses
            ->sum(function ($enrollment) {
                $duration = $enrollment->course->duration ?? "0";

                if (str_contains($duration, 'hour')) {
                    return (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);
                } elseif (str_contains($duration, 'minute')) {
                    return ((int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT)) / 60;
                }

                return 0;
            });

        // Total Completed Courses
        $totalCompletedCourses = $enrollments
            ->filter(fn($enrollment) => $enrollment->status === 'success')
            ->pluck('course.id')
            ->unique()
            ->count();

        // Pass all variables to the view
        return view(
            'backend.layouts.certificate.list',
            compact('enrollments', 'course', 'totalTrainingHours', 'totalCompletedCourses')
        );
    }
        /**
     * Download certificate PDF for an enrollment.
     */
    public function download($id)
    {
        // Load enrollment with user and course
        $enrollment = Enrollment::with(['user', 'course'])->findOrFail($id);

        // Server-side eligibility check
        if ($enrollment->status !== 'success') {
            return redirect()->back()->with('error', 'User is not eligible for certificate.');
        }

        $user = $enrollment->user;
        $course = $enrollment->course;
        $date = now()->format('d F, Y');

        $pdf = PDF::loadView('backend.layouts.certificate.certificate', compact('user', 'course', 'enrollment', 'date'))
            ->setPaper('a4', 'landscape');

        $filename = Str::slug(($user->name ?? 'user') . '-' . ($course->title ?? 'course')) . '-certificate.pdf';
        return $pdf->download($filename);
    }
}

