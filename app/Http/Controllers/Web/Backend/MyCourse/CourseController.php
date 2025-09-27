<?php

namespace App\Http\Controllers\Web\Backend\MyCourse;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\OnlineCourse;
use App\Models\Part;
use App\Models\Quiz;
use App\Models\Rating;
use App\Models\ShareExperiance;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Show user's in-progress courses.
     */
    public function inProgress()
    {
        $userId = auth()->id();

        // Total courses the user enrolled in
        $totalCourses = Enrollment::where('user_id', $userId)->count();

        // Count of in-progress courses (pending status)
        $inProgress = Enrollment::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        // Fetch in-progress courses with pagination
        $inProgressCourses = Enrollment::with('course')
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->paginate(6);

        // Count of completed courses
        $inComplete = Enrollment::where('user_id', $userId)
            ->where('status', 'success') // Adjust according to your DB
            ->count();

        return view('backend.layouts.mycourse.list', compact(
            'totalCourses',
            'inProgress',
            'inProgressCourses',
            'inComplete'
        ));
    }

    /**
     * Show a specific course with lessons, reviews, and ratings.
     */


    public function show($id)
    {
        $userId = auth()->id();

        // Fetch course with relationships
        $course = OnlineCourse::with([
            'shareExperiances.user',
            'shareExperiances.rating',
            'lessons.parts.quiz'
        ])->findOrFail($id);

        // Reviews
        $shareExperiances = $course->shareExperiances;

        // Current average rating for the course
        $rating = $course->rating;
        $ratingPoint = $rating ? $rating->rating_point : null;

        // Check if user is enrolled in this course
        $enrollment = Enrollment::where('user_id', $userId)
            ->where('course_id', $id)
            ->first();

        if (! $enrollment) {
            return redirect()->route('courses.index')
                ->with('error', 'You are not enrolled in this course.');
        }

        // Lessons & count
        $lessons = $course->lessons;
        $lessonsCount = $lessons->count();

        return view('backend.layouts.mycourse.details', compact(
            'course',
            'lessons',
            'rating',
            'ratingPoint',
            'enrollment',
            'lessonsCount',
            'shareExperiances'
        ));
    }


    /**
     * Store or update a review for a course.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'description' => 'required|string|max:2000',
        ]);

        $user = $request->user();

        // Ensure course exists
        $course = OnlineCourse::findOrFail($id);

        // Ensure user is enrolled
        $isEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $id)
            ->exists();

        if (! $isEnrolled) {
            return redirect()->back()->with('error', 'You must be enrolled in this course to leave a review.');
        }

        // Check if user already reviewed this course
        $existing = ShareExperiance::where('user_id', $user->id)
            ->where('online_course_id', $id)
            ->first();

        // Find or create rating record (1–5 scale assumed)
        $rating = Rating::firstOrCreate(['rating_point' => $request->rating]);

        $data = [
            'name'             => $user->name ?? $request->input('name', 'Student'),
            'description'      => $request->description,
            'online_course_id' => $id,
            'rating_id'        => $rating->id,
            'user_id'          => $user->id,
        ];

        // Update existing review or create new one
        $existing ? $existing->update($data) : ShareExperiance::create($data);

        // Recompute average rating for the course
        $avg = ShareExperiance::where('online_course_id', $id)
            ->join('ratings', 'share_experiences.rating_id', '=', 'ratings.id')
            ->avg('ratings.rating_point');

        if ($avg !== null) {
            // Assign nearest rating record to course
            $nearest = Rating::orderByRaw("ABS(rating_point - ?) ASC", [$avg])->first();
            if ($nearest) {
                $course->rating_id = $nearest->id;
                $course->save();
            }
        }

        return redirect()->back()->with('success', 'Thanks — your review has been submitted.');
    }

    public function content($id, $partId = null)
    {
        // load course with lessons and parts
        $course = OnlineCourse::with('lessons.parts', 'creator')->findOrFail($id);

        $lessons = $course->lessons;

        // flatten all parts into a single ordered collection
        $allParts = $lessons->pluck('parts')->flatten(1)->values();

        if ($allParts->isEmpty()) {
            abort(404, 'No course parts found.');
        }


        // find the requested part (or default to the first part)
        $currentPart = $partId ? $allParts->firstWhere('id', $partId) : null;
        $currentPart = $currentPart ?? $allParts->first();

        // find index and prev/next
        $currentIndex = $allParts->search(fn($p) => $p->id === $currentPart->id);
        $previousPart = $allParts->get($currentIndex - 1);
        $nextPart     = $allParts->get($currentIndex + 1);

        $totalParts = $allParts->count();
        $completedParts = 0; // compute from user progress as needed

        return view('backend.layouts.mycourse.course-content', compact(
            'course',
            'lessons',
            'totalParts',
            'completedParts',
            'currentPart',
            'previousPart',
            'nextPart'
        ));
    }

    public function quiz($courseId)
    {
        // Load course with lessons + parts
        $course = OnlineCourse::with('lessons.parts')->findOrFail($courseId);

        // All lessons for sidebar
        $lessons = $course->lessons;

        // Flatten all parts for progress calculation
        $allParts = $lessons->pluck('parts')->flatten(1);

        $totalParts = $allParts->count();
        $completedParts = $allParts->where('is_completed', true)->count();

        // Get current part (first part if not set)
        $currentPart = $allParts->first();

        // Load quiz for the current part
        $quiz = Quiz::where('part_id', $currentPart->id)->first(); // single quiz

        return view('backend.layouts.mycourse.quiz', compact(
            'course',
            'lessons',
            'quiz',
            'totalParts',
            'completedParts',
            'currentPart'
        ));
    }
}
