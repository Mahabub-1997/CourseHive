@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">

        {{-- ================= Dashboard Info Boxes ================= --}}
        <div class="container-fluid mb-4">
            <div class="row mt-4">
                <div class="col-12">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 2rem;">My Courses</span>
                            <span class="info-box-number" style="font-size: .7rem;">
                                You're making excellent progress in your training
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total, In Progress, Completed --}}
            <div class="row">
                {{-- Total Courses --}}
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Total Courses</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">{{ $totalCourses }}</span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                            <i class="fas fa-graduation-cap" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                </div>

                {{-- In Progress --}}
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">In Progress</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">{{ $inProgress }}</span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                            <i class="fas fa-spinner" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                </div>

                {{-- Completed --}}
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Completed</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">{{ $inComplete }}</span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                            <i class="fas fa-check-circle" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= Page Header ================= --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0">In Progress Courses</h1>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= Courses Grid ================= --}}
        <div class="container-fluid">
            <div class="row">
                @forelse($inProgressCourses as $enrollment)
                    @php $course = $enrollment->course; @endphp

                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm" style="border-radius: 15px;">

                            {{-- Course Image --}}
                            @if($course->image)
                                <img src="{{ asset('uploads/courses/' . $course->image) }}"
                                     class="card-img-top"
                                     alt="{{ $course->title }}"
                                     style="height:120px; object-fit:cover;">
                            @endif

                            {{-- Course Info --}}
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $course->title }}</h5>
                                <p class="card-text">{{ Str::limit($course->description, 100) }}</p>

                                <p class="mb-2">
                                    <strong>Price:</strong> {{ $course->price ?? 0 }}
                                    <span class="badge {{ $course->course_type == 'free' ? 'bg-success' : 'bg-warning' }}">
                                        {{ ucfirst($course->course_type) }}
                                    </span>
                                </p>
                                <p class="mb-1"><strong>Level:</strong> {{ $course->level ?? '-' }}</p>
                                <p class="mb-1"><strong>Duration:</strong> {{ $course->duration ?? '-' }}</p>
                                <p class="mb-3"><strong>Language:</strong> {{ $course->language ?? '-' }}</p>

                                {{-- Continue Button --}}
                                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary mt-auto">
                                    Continue
                                </a>
                            </div>
                        </div>
                    </div>

                @empty
                    {{-- No courses --}}
                    <div class="col-12">
                        <p class="text-muted text-center">No in-progress courses yet.</p>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
                {{ $inProgressCourses->links('pagination::bootstrap-5') }}
            </div>
        </div>

    </div>
@endsection
