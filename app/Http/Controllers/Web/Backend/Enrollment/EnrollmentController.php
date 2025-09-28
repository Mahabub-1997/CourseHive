<?php

namespace App\Http\Controllers\Web\Backend\Enrollment;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\OnlineCourse;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class EnrollmentController extends Controller
{
    /**
     *  Enroll in a free course
     */
    public function enroll($course_id)
    {
        $course = OnlineCourse::findOrFail($course_id);

        //  Only free courses can be enrolled directly
        if ($course->course_type != 'free') {
            return redirect()->back()->with('error', 'This is a paid course. Please buy it.');
        }

        //  Prevent duplicate enrollment
        $existing = Enrollment::where('user_id', Auth::id())
            ->where('course_id', $course_id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('message', 'You are already enrolled in this course.');
        }

        //  Create enrollment record
        Enrollment::create([
            'user_id'     => Auth::id(),
            'course_id'   => $course_id,
            'status'      => 'pending',
            'enrolled_at' => now(),
        ]);

        return redirect()->back()->with('message', 'You have successfully enrolled in this course!');
    }

    /**
     *  Payment page (show checkout screen)
     */
    public function pay($course_id)
    {
        $course = OnlineCourse::findOrFail($course_id);

        // Try to fetch existing enrollment if exists
        $enrollment = Enrollment::where('user_id', auth()->id())
            ->where('course_id', $course->id)
            ->first();

        return view('backend.layouts.payments.checkout', compact('course', 'enrollment'));
    }

    /**
     *  Stripe checkout session
     */
    public function checkout($course_id)
    {
        $course = OnlineCourse::findOrFail($course_id);

        //  Set Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        //  Create Stripe checkout session
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'usd',
                    'unit_amount'  => $course->price * 100, // convert to cents
                    'product_data' => ['name' => $course->title],
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => route('payment.success', $course->id) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'  => route('payment.cancel', $course->id),
        ]);

        return redirect($session->url);
    }

    /**
     *  Handle Stripe success payment
     */
    public function success(Request $request, $course_id)
    {
        $course = OnlineCourse::findOrFail($course_id);

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $session = StripeSession::retrieve($request->get('session_id'));

        if ($session->payment_status === 'paid') {
            //  Store payment record
            $payment = Payment::updateOrCreate(
                ['stripe_payment_id' => $session->payment_intent],
                [
                    'user_id'           => Auth::id(),
                    'course_id'         => $course->id,
                    'amount'            => $course->price,
                    'currency'          => 'USD',
                    'payment_method'    => 'stripe',
                    'status'            => 'success',
                ]
            );

            // Store enrollment record
            Enrollment::updateOrCreate(
                ['user_id' => Auth::id(), 'course_id' => $course->id],
                [
                    'status'      => 'success',
                    'payment_id'  => $payment->id,
                    'enrolled_at' => now(), // ✅ Free course er motoi enrolled_at save hobe
                ]
            );

            return redirect()->route('my.courses')
                ->with('success', '✅ Payment successful! Your enrollment is complete.');
        }

        return redirect()->route('courses.pay', $course->id)
            ->with('error', 'Payment failed. Please try again.');
    }

    /**
     *  Handle Stripe cancel payment
     */
    public function cancel($course_id)
    {
        return redirect()->route('courses.pay', $course_id)
            ->with('error', 'Payment canceled.');
    }

    /**
     *  Manual / custom payment processing (admin use)
     */
    public function processPayment(Request $request, $course_id)
    {
        $course = OnlineCourse::findOrFail($course_id);

        $request->validate([
            'payment_method'     => 'required|in:stripe,paypal,manual',
            'stripe_payment_id'  => 'nullable|string|max:255',
        ]);

        $status = ($request->payment_method === 'stripe') ? 'success' : 'pending';

        // Create payment record
        $payment = Payment::create([
            'user_id'          => Auth::id(),
            'course_id'        => $course->id,
            'amount'           => $course->price,
            'currency'         => 'USD',
            'payment_method'   => $request->payment_method,
            'stripe_payment_id'=> $request->stripe_payment_id ?? null,
            'status'           => $status,
        ]);

        // Update enrollment if payment is successful
        if ($status === 'success') {
            Enrollment::updateOrCreate(
                ['user_id' => Auth::id(), 'course_id' => $course->id],
                [
                    'status'      => 'pending',
                    'payment_id'  => $payment->id,
                    'enrolled_at' => now(),
                ]
            );
        }

        return redirect()->back()
            ->with('success', $status === 'success' ? '✅ Payment successful!' : 'Payment pending.');
    }

    /**
     *  Admin: List all payments
     */
    public function index()
    {
        $payments = Payment::with(['user', 'course'])->latest()->paginate(20);
        return view('backend.layouts.payments.index', compact('payments'));
    }

    /**
     *  Show logged-in user's courses
     */
    public function myCourses()
    {
        $enrollments = Auth::user()->enrollments()
            ->with('course')
            ->where('status', 'success') // ✅ Only successful enrollments
            ->orderBy('created_at', 'desc')
            ->get();

        return view('courses.my_courses', compact('enrollments'));
    }

    /**
     *  Show all enrollments for logged-in user
     */
    public function indexEnrollments()
    {
        $userId = auth()->id();

        $enrollments = Enrollment::with(['user', 'course'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $course = null; // ✅ always pass $course (null means "all")
        return view('backend.layouts.enrollment.list', compact('enrollments', 'course'));
    }

    /**
     *Show all users enrolled in a specific course
     */
    public function courseEnrolledUsers($course_id)
    {
        $course = OnlineCourse::with('enrollments.user')->findOrFail($course_id);

        $enrollments = $course->enrollments()
            ->with('user')
            ->orderBy('enrolled_at', 'desc')
            ->get();

        return view('backend.layouts.enrollment.list', compact('enrollments', 'course'));
    }

    /**
     * Admin: Update enrollment status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,success,failed', // ✅ must match DB enum values
        ]);

        $enrollment = Enrollment::findOrFail($id);
        $enrollment->status = $request->status;
        $enrollment->save();

        return redirect()->back()->with('message', 'Enrollment status updated successfully!');
    }

    /**
     *  Show top 5 most enrolled courses
     */
    public function topCourses()
    {
        $topCourses = OnlineCourse::withCount(['enrollments as enrollments_count' => function ($query) {
            $query->where('status', 'success'); // ✅ count only successful enrollments
        }])
            ->orderByDesc('enrollments_count')
            ->take(1)
            ->get();

        return view('backend.layouts.top_course.list', compact('topCourses'));
    }
}
