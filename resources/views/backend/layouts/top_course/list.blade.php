@extends('backend.partials.master')
@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <h1 class="m-0">📚 Top 5 Courses </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid mb-4">
            <div class="row">
                @forelse($topCourses as $course)
                    <div class="col-12 col-sm-6 col-md-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <img src="{{ asset('uploads/courses/' . $course->image) }}" class="card-img-top"  style="height:200px; object-fit:cover;">
                            <div class="card-body">
                                <h5 class="card-title fw-bold">{{ $course->title }}</h5>
                                <p class="card-text text-muted">{{ $course->subtitle }}</p>
                                <p class="text-primary fw-bold mb-0">
                                    <i class="fas fa-users"></i> {{ $course->enrollments_count }} Successful Enrollments
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center">No courses found!</div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
