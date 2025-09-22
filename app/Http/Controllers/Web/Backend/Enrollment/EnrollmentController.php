<?php

namespace App\Http\Controllers\Web\Backend\Enrollment;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\OnlineCourse;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    // 1️⃣ Enroll in a free course
    public function enroll($course_id)
    {
        $course = OnlineCourse::findOrFail($course_id);

        // Only free courses can be enrolled directly
        if ($course->course_type != 'free') {
            return redirect()->back()->with('error', 'This is a paid course. Please buy it.');
        }

        // Check if already enrolled
        $existing = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course_id)
            ->first();
        if ($existing) {
            return redirect()->back()->with('message', 'You are already enrolled in this course.');
        }

        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $course_id,
            'status' => 'pending',
            'enrolled_at' => now(),
        ]);

        return redirect()->back()->with('message', 'You have successfully enrolled in this course!');
    }

    // 2️⃣ Redirect to payment page for paid courses
//    public function pay($course_id)
//    {
//        $course = OnlineCourse::findOrFail($course_id);
//
//        if ($course->course_type != 'paid') {
//            return redirect()->back()->with('error', 'This course is free. You can enroll directly.');
//        }
//
//        // Here you can redirect to your payment gateway page
////        return redirect()->route('payment.pay', $course_id);
//        return view('backend.layouts.payments.checkout', compact('course'));
//    }

    public function pay($course_id)
    {
        $course = OnlineCourse::findOrFail($course_id);

        if ($course->course_type != 'paid') {
            return redirect()->back()->with('error', 'This course is free. You can enroll directly.');
        }

        // Get the current user's enrollment for this course, if any
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first();

        return view('backend.layouts.payments.checkout', compact('course', 'enrollment'));
    }
    public function processPayment(Request $request, $course_id)
    {
        $course = OnlineCourse::findOrFail($course_id);

        $request->validate([
            'payment_method' => 'required|in:stripe,paypal,manual',
        ]);

        // Create payment record
        $payment = Payment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'amount' => $course->price,
            'currency' => 'USD',
            'payment_method' => $request->payment_method,
            'status' => 'completed', // mark as completed for now
        ]);

        return redirect()->route('courses.enroll', $course->id)
            ->with('success', 'Payment successful! You are now enrolled.');
    }

    // 3️⃣ Handle successful payment callback
//    public function paymentSuccess(Request $request, $course_id)
//    {
//        $course = OnlineCourse::findOrFail($course_id);
//
//        // Check if already enrolled
//        $existing = Enrollment::where('user_id', Auth::id())
//            ->where('course_id', $course_id)
//            ->first();
//        if ($existing) {
//            return redirect()->route('courses.index')->with('message', 'You are already enrolled in this course.');
//        }
//
//        Enrollment::create([
//            'user_id' => Auth::id(),
//            'course_id' => $course_id,
//            'status' => 'active',
//            'enrolled_at' => now(),
//        ]);
//
//        return redirect()->route('courses.index')->with('message', 'Payment successful! You are now enrolled.');
//    }

    // 4️⃣ Optional: Show user's enrolled courses
    public function myCourses()
    {
        $enrollments = Auth::user()->enrollments()->with('course')->get();
        return view('courses.my_courses', compact('enrollments'));
    }



//    -------------list -----------

    // All enrollments (admin/list page)
//    public function indexEnrollments()
//    {
//        $enrollments = Enrollment::with(['user', 'course'])
//            ->orderBy('created_at', 'desc')
//            ->paginate(15); // or ->get() if you prefer
//
//        $course = null; // always pass $course (null means "all")
//        return view('backend.layouts.enrollment.list', compact('enrollments', 'course'));
//    }
    public function indexEnrollments()
    {
        $userId = auth()->id(); // লগইন ইউজারের আইডি নিলাম

        $enrollments = Enrollment::with(['user', 'course'])
            ->where('user_id', $userId) // শুধু তার নিজের enrollment
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $course = null; // always pass $course (null means "all")
        return view('backend.layouts.enrollment.list', compact('enrollments', 'course'));
    }

    // Enrollments for a single course
    public function courseEnrolledUsers($course_id)
    {
        $course = OnlineCourse::with('enrollments.user')->findOrFail($course_id);

        // get enrollments but eager-load user relation
        $enrollments = $course->enrollments()->with('user')->orderBy('enrolled_at', 'desc')->get();

        return view('backend.layouts.enrollment.list', compact('enrollments', 'course'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed' // must match DB enum values
        ]);

        $enrollment = Enrollment::findOrFail($id);
        $enrollment->status = $request->status;
        $enrollment->save();

        return redirect()->back()->with('message', 'Enrollment status updated successfully!');
    }



    public function topCourses()
    {
        // Get courses with ONLY successful enrollments counted
        $topCourses = OnlineCourse::withCount(['enrollments as enrollments_count' => function ($query) {
            $query->where('status', 'success');
        }])
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();

        return view('backend.layouts.top_course.list', compact('topCourses'));
    }
}
