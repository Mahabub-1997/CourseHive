
{{--@extends('backend.partials.master')--}}

{{--@section('content')--}}
{{--    <div class="content-wrapper">--}}
{{--        <div class="content-header">--}}
{{--            <div class="container-fluid">--}}
{{--                <div class="row mb-2">--}}
{{--                    <div class="col-sm-6">--}}
{{--                        <h1 class="m-0">Payment for Course</h1>--}}
{{--                        <p class="text-muted">Course: {{ $course->title }} | Price: ${{ number_format($course->price, 2) }}</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="container-fluid mb-4">--}}
{{--            <div class="row mt-4">--}}
{{--                <div class="col-12">--}}
{{--                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">--}}
{{--                        <div class="info-box-content">--}}
{{--                            <span class="info-box-text fw-bold" style="font-size: 2rem;">Your Payment</span>--}}
{{--                            <span class="info-box-number" style="font-size: .7rem;">--}}
{{--                            Complete the payment to enroll in this course--}}
{{--                        </span>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            --}}{{-- Payment Table --}}
{{--            <div class="row">--}}
{{--                <div class="col-12">--}}
{{--                    <div class="card">--}}
{{--                        <div class="card-body">--}}
{{--                            @php--}}
{{--                                $enrollment = $course->enrollments()->where('user_id', auth()->id())->first();--}}
{{--                            @endphp--}}

{{--                            <div class="table-responsive">--}}
{{--                                <table class="table table-bordered table-striped">--}}
{{--                                    <thead class="bg-gradient-teal text-white">--}}
{{--                                    <tr>--}}
{{--                                        <th>#</th>--}}
{{--                                        <th>User Name</th>--}}
{{--                                        <th>User Email</th>--}}
{{--                                        <th>Course Title</th>--}}
{{--                                        <th>Price</th>--}}
{{--                                        <th class="text-center">Action</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    <tr>--}}
{{--                                        <td>1</td>--}}
{{--                                        <td>{{ auth()->user()->name }}</td>--}}
{{--                                        <td>{{ auth()->user()->email }}</td>--}}
{{--                                        <td>{{ $course->title }}</td>--}}
{{--                                        <td>${{ number_format($course->price, 2) }}</td>--}}
{{--                                        <td class="text-center">--}}
{{--                                            @if(!$enrollment || $enrollment->status == 'pending')--}}
{{--                                                <a href="{{ route('courses.pay', $course->id) }}" class="btn btn-success btn-sm">--}}
{{--                                                    Pay Now--}}
{{--                                                </a>--}}
{{--                                            @else--}}
{{--                                                <span class="badge bg-success">Paid</span>--}}
{{--                                            @endif--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                            </div>--}}

{{--                            --}}{{-- Optional: show message if already paid --}}
{{--                            @if($enrollment && $enrollment->status == 'success')--}}
{{--                                <div class="alert alert-success mt-3">--}}
{{--                                    You have successfully paid for this course.--}}
{{--                                </div>--}}
{{--                            @endif--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}
@extends('backend.partials.master')



        @section('content')
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">Payment for Course</h1>
                                <p class="text-muted">Course: {{ $course->title }} | Price: ${{ number_format($course->price, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid mb-4">
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                                <div class="info-box-content">
                                    <span class="info-box-text fw-bold" style="font-size: 2rem;">Your Payment</span>
                                    <span class="info-box-number" style="font-size: .7rem;">
                            Complete the payment to enroll in this course
                        </span>
                                </div>
                            </div>
                        </div>
                    </div>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="bg-gradient-teal text-white">
                <tr>
                    <th>#</th>
                    <th>User Name</th>
                    <th>User Email</th>
                    <th>Course Title</th>
                    <th>Price</th>
                    <th class="text-center">Action</th>
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
                        @if(!$enrollment || $enrollment->status == 'pending')
                            <!-- Button triggers payment method selection modal -->
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#paymentModal">
                                Pay Now
                            </button>
                        @else
                            <span class="badge bg-success">Paid</span>
                        @endif
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <!-- Payment Method Modal -->
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('payment.process', $course->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">Select Payment Method</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Payment Method:</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="">-- Choose Payment Method --</option>
                                    <option value="stripe">Stripe</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="manual">Bank Transfer / Manual</option>
                                </select>
                                @error('payment_method')
                                <p class="text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <p>Amount to Pay: <strong>${{ number_format($course->price, 2) }}</strong></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-success">Pay Now</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
