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

  public function createPayment(Request $request, $course_id)
{
    $course = OnlineCourse::findOrFail($course_id);

    $request->validate([
        'payment_method' => 'required|in:stripe,paypal,manual',
        'success_url' => 'required|url',
        'cancel_url' => 'required|url',
    ]);

    if ($request->payment_method !== 'stripe') {
        // Handle manual or PayPal payment
        $payment = Payment::create([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
            'amount' => $course->price,
            'currency' => 'USD',
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);

        return response()->json([
            'payment_url' => $request->cancel_url, // Or your manual payment page
            'payment_id' => $payment->id,
        ]);
    }

    // Stripe payment
    Stripe::setApiKey(env('STRIPE_SECRET'));

    $session = StripeSession::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'usd',
                'product_data' => [
                    'name' => $course->title,
                ],
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

    // Save payment as pending
    $payment = Payment::create([
        'user_id' => Auth::id(),
        'course_id' => $course->id,
        'amount' => $course->price,
        'currency' => 'USD',
        'payment_method' => 'stripe',
        'status' => 'pending',
        'stripe_payment_id' => $session->id
    ]);

    // Return Stripe payment URL
    return response()->json([
        'payment_url' => $session->url,
        'payment_id' => $payment->id,
    ]);
}

}
