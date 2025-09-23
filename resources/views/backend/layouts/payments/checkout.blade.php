@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        {{-- Page Header --}}
        <div class="content-header">
            <div class="container-fluid">
                <h1>Payment for Course</h1>
                <p class="text-muted">
                    Course: {{ $course->title }} |
                    Price: ${{ number_format($course->price, 2) }}
                </p>
            </div>
        </div>

        <div class="container-fluid mb-4">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-2">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-2">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @php
                $enrollment = $enrollment ?? null;
            @endphp

            {{-- Enrollment Table --}}
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-gradient-teal text-white">
                            <tr>
                                <th>#</th>
                                <th>User</th>
                                <th>Email</th>
                                <th>Course</th>
                                <th>Price</th>
                                <th class="text-center">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>{{ auth()->user()->name }}</td>
                                <td>{{ auth()->user()->email }}</td>
                                <td>{{ $course->title }}</td>
                                <td>${{ number_format($course->price, 2) }}</td>
                                <td class="text-center">
                                    @if($enrollment && $enrollment->status === 'success')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Stripe Payment Section --}}
            @if(!$enrollment || $enrollment->status !== 'success')
                <div class="card card-shadow rounded-4">
                    <div class="card-header text-center text-white"
                         style="background: linear-gradient(90deg, #4facfe, #00f2fe);">
                        <h4>💳 Stripe Payment</h4>
                    </div>
                    <div class="card-body bg-light">
                        {{-- Payment Form --}}
                        <form action="{{ route('payment.process', $course->id) }}" method="POST" id="stripe-form">
                            @csrf
                            <input type="hidden" name="price" value="{{ $course->price }}">
                            <input type="hidden" name="stripeToken" id="stripe-token">
                            <input type="hidden" name="payment_method" value="stripe">

                            <div class="form-group mb-3">
                                <label for="card-element" class="fw-bold text-primary">Card Details:</label>
                                <div id="card-element"
                                     class="form-control border-2"
                                     style="padding: 12px; border-radius: 6px;"></div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="button" onclick="createToken()" class="gradient-btn py-2">
                                    🚀 Submit Payment
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center small text-muted" style="background: #eef2f7;">
                        🔒 Secure Payment Powered by Stripe
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Stripe JS --}}
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe("{{ env('STRIPE_KEY') }}");
        const elements = stripe.elements();

        // Create card input
        const card = elements.create('card', {hidePostalCode: true});
        card.mount('#card-element');

        // Tokenize and submit form
        function createToken() {
            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    alert(result.error.message);
                } else {
                    document.getElementById('stripe-token').value = result.token.id;
                    document.getElementById('stripe-form').submit();
                }
            });
        }
    </script>
@endsection
