<?php

namespace App\Http\Controllers\Web\Backend\Payment;

use App\Http\Controllers\Controller;
use App\Models\OnlineCourse;
use App\Models\Payment;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;



class PaymentController extends Controller
{
    // Show checkout page
    public function pay($course_id)
    {
        $course = OnlineCourse::findOrFail($course_id);

        if ($course->course_type != 'paid') {
            return redirect()->back()->with('error', 'This course is free. You can enroll directly.');
        }

        return view('backend.layouts.payments.checkout', compact('course'));
    }

    // Handle checkout form submission
    public function checkout(Request $request, $course_id)
    {
        $user = auth()->user();
        $course = OnlineCourse::findOrFail($course_id);
        $amount = $course->price;

        // Apply promo code
        $promo = null;
        if ($request->promo_code) {
            $promo = PromoCode::where('code', $request->promo_code)->first();
            if (!$promo || !$promo->isValid()) {
                return redirect()->back()->with('error', 'Invalid or expired promo code.');
            }

            if ($promo->type === 'percentage') {
                $amount -= ($amount * $promo->value / 100);
            } else {
                $amount -= $promo->value;
            }

            $amount = max($amount, 0);
        }

        // Stripe PaymentIntent
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $paymentIntent = PaymentIntent::create([
            'amount' => round($amount * 100), // in cents
            'currency' => 'usd',
            'payment_method_types' => ['card'],
            'metadata' => [
                'user_id' => $user->id,
                'course_id' => $course->id,
                'promo_code_id' => $promo->id ?? null,
            ],
        ]);

        // Create pending payment
        $payment = Payment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'amount' => $amount,
            'currency' => 'USD',
            'status' => 'pending',
            'promo_code_id' => $promo->id ?? null,
            'stripe_payment_id' => $paymentIntent->id,
        ]);

        return view('backend.layouts.payments.stripe', compact('paymentIntent', 'course', 'payment'));
    }

    // Payment success
    public function success($payment_id)
    {
        $payment = Payment::findOrFail($payment_id);
        $payment->status = 'success';
        $payment->save();

        // Increment promo used count
        if ($payment->promoCode) {
            $payment->promoCode->increment('used_count');
        }

        // Enroll user
        $payment->user->enrolledCourses()->attach($payment->course_id, [
            'status' => 'success',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('courses.show', $payment->course_id)
            ->with('message', 'Payment successful! You are now enrolled.');
    }

    // Payment cancel
    public function cancel()
    {
        return redirect()->back()->with('error', 'Payment was canceled.');
    }
}


