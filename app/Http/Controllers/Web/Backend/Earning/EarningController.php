<?php

namespace App\Http\Controllers\Web\Backend\Earning;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class EarningController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index()
    {
        // Load payments with user, course, and promo code
        $payments = Payment::with(['user', 'course', 'promoCode'])->latest()->paginate(20);

        return view('backend.layouts.earning.list', compact('payments'));
    }
}
