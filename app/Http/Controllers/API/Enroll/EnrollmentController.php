<?php

namespace App\Http\Controllers\API\Enroll;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\OnlineCourse;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class EnrollmentController extends Controller
{
    /**
     * Handle course enrollment
     *
     * @param Request $request
     * @param int $course_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function enroll(Request $request, $course_id)
    {
        $user = Auth::user();
        $course = OnlineCourse::find($course_id);

        //  Course not found
        if (!$course) {
            return response()->json([
                'status' => 'error',
                'message' => 'Course not found.'
            ], 404);
        }

        //  Check if user already enrolled
        $existing = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course_id)
            ->first();

        if ($existing) {
            // If status is pending, check payment confirmation
            if ($existing->status === 'pending') {
                $payment = Payment::where('id', $existing->payment_id)
                    ->where('status', 'success')
                    ->first();

                if ($payment) {
                    // Update enrollment after successful payment
                    $existing->status = 'success';
                    $existing->enrolled_at = now();
                    $existing->save();

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Payment confirmed. You are now enrolled!',
                        'enrollment_status' => $existing->status
                    ], 200);
                }
            }

            // Already enrolled (success or still pending)
            return response()->json([
                'status' => 'success',
                'message' => 'You are already enrolled in this course.',
                'enrollment_status' => $existing->status
            ], 200);
        }

        // 🎓 Free course → Enroll directly
        if ($course->course_type === 'free') {
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'status' => 'success', // Direct success for free courses
                'enrolled_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'You have successfully enrolled in this free course!',
                'enrollment_status' => $enrollment->status
            ], 201);
        }

        // 💳 Paid course → check if user already paid
        $successfulPayment = Payment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->where('status', 'success')
            ->first();

        if ($successfulPayment) {
            // Enroll directly since payment already successful
            $enrollment = Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'status' => 'success',
                'payment_id' => $successfulPayment->id,
                'enrolled_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment already completed. You are now enrolled!',
                'enrollment_status' => $enrollment->status
            ], 201);
        }

        // 💰 Paid course → Create Stripe checkout session
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $course->title],
                    'unit_amount' => $course->price * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $request->success_url . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $request->cancel_url,
            'metadata' => [
                'user_id' => $user->id,
                'course_id' => $course->id,
            ]
        ]);

        // Save payment as pending
        $payment = Payment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $course->price,
            'currency' => 'USD',
            'payment_method' => 'stripe',
            'status' => 'pending',
            'stripe_payment_id' => $session->id
        ]);

        // Save enrollment as pending
        $enrollment = Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'pending',
            'payment_id' => $payment->id,
            'enrolled_at' => now(),
        ]);

        return response()->json([
            'status' => 'pending',
            'message' => 'Payment required for this course. Please complete payment.',
            'payment_url' => $session->url,
            'payment_id' => $payment->id,
            'session_id' => $session->id,
            'enrollment_status' => $enrollment->status
        ], 200);
    }

    /**
     * Create payment for a course (Stripe, PayPal, or manual)
     *
     * @param Request $request
     * @param int $course_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPayment(Request $request, $course_id)
    {
        $course = OnlineCourse::findOrFail($course_id);

        // Validate input
        $request->validate([
            'payment_method' => 'required|in:stripe,paypal,manual',
            'success_url' => 'required|url',
            'cancel_url' => 'required|url',
        ]);

        // 📝 Handle Manual or PayPal payment (direct success)
        if ($request->payment_method !== 'stripe') {
            $payment = Payment::create([
                'user_id' => Auth::id(),
                'course_id' => $course->id,
                'amount' => $course->price,
                'currency' => 'USD',
                'payment_method' => $request->payment_method,
                'status' => 'success',
            ]);

            return response()->json([
                'payment_url' => $request->cancel_url, // Redirect to manual page
                'payment_id' => $payment->id,
            ]);
        }

        // 💳 Stripe Payment
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => ['name' => $course->title],
                    'unit_amount' => $course->price * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $request->success_url . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $request->cancel_url,
            'metadata' => [
                'user_id' => Auth::id(),
                'course_id' => $course->id,
            ]
        ]);

        // Save payment as pending until Stripe confirms
        $payment = Payment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'amount' => $course->price,
            'currency' => 'USD',
            'payment_method' => 'stripe',
            'status' => 'pending',
            'stripe_payment_id' => $session->id
        ]);

        return response()->json([
            'payment_url' => $session->url,
            'payment_id' => $payment->id,
            'session_id' => $session->id,
        ]);
    }
}
