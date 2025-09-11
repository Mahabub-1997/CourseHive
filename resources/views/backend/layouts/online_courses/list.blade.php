
@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12 align-items-center"></div>
                    <div class="col-sm-6">
                        <h1 class="m-0">Online Course</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right"></ol>
                    </div>
                </div>
            </div>
        </div>

{{--        Dashboard Info Boxes--}}
        <div class="container-fluid mb-4">
            <div class="row mt-4">
                <div class="col-12">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 2rem;">My Course</span>
                            <span class="info-box-number" style="font-size: .7rem;">
                                You're making excellent progress in your healthcare training
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Total Courses Card -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Total Courses</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;"> {{ $totalCourses }}</span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                            <i class="fas fa-graduation-cap" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                </div>

                <!-- In Progress Card -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">In Progress</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">{{ $inProgress }}</span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                            <i class="fas fa-graduation-cap" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                </div>

                <!-- Completed Card -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Completed</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">{{$inComplete}}</span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                            <i class="fas fa-graduation-cap" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Header --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0">All Online Courses</h1>
                    </div>
                    <div class="col-sm-6 d-flex justify-content-end">
                        <a href="{{ route('online-courses.create') }}" class="btn bg-gradient-teal btn-sm">
                            <i class="fa fa-plus text-light"></i> Add New Course
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        @if(Session::get('message'))
            <div class="alert alert-success alert-dismissible col-md-6 mx-auto">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> {{ Session::get('message') }}</h5>
            </div>
        @endif

        {{-- Courses Grid --}}
        <div class="container-fluid">
            <div class="row">
                @foreach($courses as $course)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm" style="width: 500px; height: 500px;">
                            @if($course->image)
                                <img src="{{ asset('uploads/courses/' . $course->image) }}" class="card-img-top" alt="{{ $course->title }}" style="height:120px; object-fit:cover;">
                            @endif
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

                                {{-- Action Buttons --}}
                                @if($course->course_type == 'free')
                                    <form action="{{ route('courses.enroll', $course->id) }}" method="POST" class="mt-auto">

                                        @csrf
                                        <button type="submit" class="btn btn-success btn-block">Enroll</button>
                                    </form>
                                @else
                                    <a href="{{ route('courses.pay', $course->id) }}" class="btn btn-primary btn-block mt-auto">Buy Now</a>

                                @endif

                                {{-- Admin Actions --}}
                                <div class="mt-2 d-flex justify-content-between">
                                    <a href="{{ route('online-courses.edit', $course->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('online-courses.destroy', $course->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">

                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Delete</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center">
{{--                {{ $courses->links() }}--}}
            </div>
        </div>

    </div>
@endsection
