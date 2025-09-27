@extends('backend.partials.master')

@section('title', 'Payments')

@section('content')
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12 align-items-center"></div>
                    <div class="col-sm-6">
                        <h1 class="m-0">Payments List</h1>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Cards --}}
        <div class="container-fluid mb-4">
            <div class="row mt-4">
                <!-- Total Payments -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Total Payments</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">{{ $payments->count() }}</span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                        <i class="fas fa-credit-card" style="font-size: 2rem;"></i>
                    </span>
                    </div>
                </div>

                <!-- Successful Payments -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Success</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">
                            {{ $payments->where('status', 'success')->count() }}
                        </span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                        <i class="fas fa-check-circle" style="font-size: 2rem;"></i>
                    </span>
                    </div>
                </div>

                <!-- total Payments -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Total Amount</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">
                                   ${{ number_format($payments->sum('amount'), 2) }}
                            </span>
                        </div>
                            <span class="info-box-icon bg-white elevation-1 text-dark">
                                <i class="fas fa-dollar-sign" style="font-size: 2rem;"></i>
                            </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body">
                @if(Session::get('message'))
                    <div class="alert alert-success alert-dismissible col-md-5">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> {{ Session::get('message') }}</h5>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-gradient-teal text-white">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Course</th>
                            <th>Amount</th>
                            <th>Currency</th>
                            <th>Payment Method</th>
                            <th>Status</th>
{{--                            <th>Promo Code</th>--}}
                            <th>Paid At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $payment->user->name ?? 'N/A' }}</td>
                                <td>{{ $payment->course->title ?? 'N/A' }}</td>
                                <td>{{ number_format($payment->amount, 2) }}</td>
                                <td>{{ strtoupper($payment->currency) }}</td>
                                <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                                <td>
                                    <span class="badge
                                        @if($payment->status == 'success') bg-success
                                        @elseif($payment->status == 'pending') bg-warning
                                        @else bg-danger @endif">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
{{--                                <td>{{ $payment->promoCode->code ?? '-' }}</td>--}}
                                <td>{{ $payment->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">No payments found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    @if(method_exists($payments, 'links'))
                        <div class="mt-3">
                            {{ $payments->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
