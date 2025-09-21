@extends('backend.partials.master')
@section('content')
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12 align-items-center"></div>
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            @if(isset($course) && $course)
                                Users Enrolled in: {{ $course->title }}
                            @else
                                All Certificate
                            @endif
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Cards --}}
        <div class="container-fluid mb-4">
            <div class="row mt-4">
                <!-- Completed Enrollments -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Earned Certificates</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">
                                {{ $enrollments->where('status', 'success')->count() }}
                            </span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                            <i class="fas fa-graduation-cap" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                </div>
                <!-- Total Enrollments -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">In Progress</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">
                                {{ $enrollments->where('status', 'pending')->count() }}
                            </span>
                        </div>
                            <span class="info-box-icon bg-white elevation-1 text-dark">
                               <i class="fas fa-users" style="font-size: 2rem;"></i>
                            </span>
                    </div>
                </div>

                <!-- Total Training Hours -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Total Training Hours</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">
                                {{ number_format($totalTrainingHours) }}h
                            </span>
                        </div>
                            <span class="info-box-icon bg-white elevation-1 text-dark">
                                <i class="fas fa-clock" style="font-size: 2rem;"></i>
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

                @if(Session::get('error'))
                    <div class="alert alert-danger alert-dismissible col-md-5">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-exclamation-circle"></i> {{ Session::get('error') }}</h5>
                    </div>
                @endif

                    <div class="row">
                        @forelse($enrollments as $enrollment)
                            @if($enrollment->status === 'success')
                                <div class="col-12 col-md-12 mb-3">
                                    <div class="card shadow-sm">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <div class="me-3">
                                                    <i class="fas fa-certificate fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <h5 class="card-title mb-1">{{ $enrollment->course->title ?? 'N/A' }}</h5>
                                                    <p class="mb-0 text-muted" style="font-size: 0.875rem;"><br>
                                                        Instructor:{{ $enrollment->course->instructors->name  ?? 'N/A' }}<br>
                                                        Issued: {{ \Carbon\Carbon::parse($enrollment->enrolled_at ?? $enrollment->created_at)->format('m/d/Y') }}
                                                        Duration: {{ $enrollment->course->duration ?? 'N/A' }}<br>
                                                        Credential Names: {{ $enrollment->user->name ?? 'N/A' }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div>
                                                <a href="{{ route('certificate.download', $enrollment->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-download"></i> Download PDF
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="col-12">
                                <p class="text-center text-muted">No completed enrollments found.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Certificates in Progress</h5>
                            <small class="text-muted">Certificates you've successfully earned</small>
                        </div>
                        <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                            @php
                                use App\Models\Enrollment;
                                $inProgressEnrollments = Enrollment::with('course')
                                ->where('user_id', auth()->id())
                                ->where('status', 'pending') // or 'in_progress' if that’s correct
                                ->get();
                            @endphp

                            @forelse($inProgressEnrollments as $enrollment)
                                <div class="mb-3 p-3 bg-light rounded d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $enrollment->course->title ?? 'Untitled Course' }}</h6>

                                        <div class="progress mb-1" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width: {{ $enrollment->progress ?? 0 }}%;"
                                                 aria-valuenow="{{ $enrollment->progress ?? 0 }}"
                                                 aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>

                                        <small class="text-muted">
                                            Progress: {{ $enrollment->progress ?? 0 }}% <br>
                                            Est. completion: {{ \Carbon\Carbon::parse($enrollment->estimated_completion ?? now())->format('m/d/Y') }}
                                        </small>
                                    </div>

                                    <div>
                                        @if($enrollment->course)
                                            <a href=" {{ route('courses.show', $enrollment->course->id) }}" class="btn btn-primary btn-sm">
                                                Continue Course
                                            </a>
                                        @else
                                            <span class="text-muted">No course link</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-center text-muted">No courses in progress.</p>
                            @endforelse
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
