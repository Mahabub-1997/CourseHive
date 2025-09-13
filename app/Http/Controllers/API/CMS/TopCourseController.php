<?php

namespace App\Http\Controllers\API\CMS;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourse;
use App\Models\TopCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TopCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $topCourses = OnlineCourse::withCount(['enrollments as enrollments_count' => function ($query) {
            $query->where('status', 'success'); // only successful enrollments
        }])
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'subtitle' => $course->subtitle,
                    'description' => $course->description,
                    'enrollments_count' => $course->enrollments_count,
                    'image' => $course->image
                        ? asset('uploads/courses/' . $course->image)
                        : asset('images/default-course.png')
                ];
            });

        return response()->json([
            'status' => 'success',
            'data' => $topCourses
        ]);
    }
}
