@extends('layouts.app')

@section('content')
    <div class="container mx-auto mt-10 max-w-lg">
        <div class="card p-6 shadow rounded-lg">
            <h2 class="text-2xl font-bold mb-4">Pay for {{ $course->title }}</h2>

            <p class="mb-4">Price: ${{ number_format($course->price, 2) }}</p>

            <form action="{{ route('payment.process', $course->id) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="block font-medium mb-2">Select Payment Method:</label>
                    <select name="payment_method" class="border p-2 w-full rounded" required>
                        <option value="">-- Choose Payment Method --</option>
                        <option value="stripe">Stripe</option>
                        <option value="paypal">PayPal</option>
                        <option value="manual">Bank Transfer / Manual</option>
                    </select>
                    @error('payment_method')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Pay ${{ number_format($course->price, 2) }}
                </button>
            </form>
        </div>
    </div>
@endsection
