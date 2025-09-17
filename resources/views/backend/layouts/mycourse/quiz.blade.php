
@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12 align-items-center">
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <h1 class="m-0">Quiz </h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">

                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="container-fluid mb-4">
            <div class="row mt-4">
                <div class="col-12">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 2rem;">My Quiz</span>
                            <span class="info-box-number" style="font-size: .7rem;">
                                Learning every day with practice and patience helps students achieve success, knowledge, and confidence in life.
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Table Section --}}
        <div class="card">
            <div class="card-body">
                @if(Session::get('message'))
                    <div class="alert alert-success alert-dismissible col-md-5">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> {{ Session::get('message') }}</h5>
                    </div>
                @endif
                    <div class="card">
                        <div class="card-body">
                            @if(Session::get('success'))
                                <div class="alert alert-success alert-dismissible col-md-5">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <h5><i class="icon fas fa-check"></i> {{ Session::get('success') }}</h5>
                                </div>
                            @endif

                            <div class="container-fluid mt-4">
                                <div class="row">
                                    <!-- Left Sidebar: Course Content -->
                                    <div class="col-md-4 col-lg-3">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-white">
                                                <h5 class="card-title mb-1">Course Content</h5>
                                                <small>{{ $completedParts }}/{{ $totalParts }} videos completed</small>
                                                <!-- Progress Bar -->
                                                <div class="progress mt-2" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                         style="width: {{ ($completedParts/$totalParts)*100 }}%;"
                                                         aria-valuenow="{{ ($completedParts/$totalParts)*100 }}" aria-valuemin="0"
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-unstyled">
                                                    @foreach($lessons as $lesson)
                                                        <li class="mb-3">
                                                            <!-- Lesson Title -->
                                                            <div class="d-flex justify-content-between align-items-center lesson-toggle"
                                                                 data-bs-toggle="collapse"
                                                                 data-bs-target="#lesson{{ $lesson->id }}"
                                                                 aria-expanded="false"
                                                                 style="cursor: pointer; padding:8px 12px; background:#f8f9fa; border-radius:6px;">
                                                                <span class="fw-semibold">{{ $lesson->title }}</span>
                                                                <i class="bi bi-chevron-down"></i>
                                                            </div>

                                                            <!-- Lesson Parts -->
                                                            <div id="lesson{{ $lesson->id }}" class="collapse ps-3 mt-2 text-black">
                                                                @if($lesson->parts->count())
                                                                    <ul class="list-unstyled">
                                                                        @foreach($lesson->parts as $part)
                                                                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                                                <!-- Part Title -->
                                                                                <div>
                                                                                    <i class="bi bi-play-circle me-2 text-primary"></i>
                                                                                    {{ $part->title }}
                                                                                    <small class="text-muted">• {{ $part->duration ?? '00:00' }}</small>
                                                                                </div>

                                                                                <!-- Video Link -->
                                                                                <div>
                                                                                    @if($part->video)
                                                                                        <a href="{{ $part->video }}" target="_blank"
                                                                                           class="text-decoration-none text-primary">
                                                                                            <i class="bi bi-camera-video me-1"></i> Watch
                                                                                        </a>
                                                                                    @endif
                                                                                </div>
                                                                            </li>
                                                                        @endforeach

                                                                        <!-- Quiz & Continue Button -->
                                                                        <div class="mt-2 d-flex justify-content-between align-items-center">
                                                                            <div class="badge bg-success text-decoration-none">
                                                                                <i class="bi bi-question-circle me-1"></i> Quiz
                                                                            </div>
                                                                            <a href="{{ route('course.quiz', $course->id) }}"
                                                                               class="btn btn-primary btn-sm px-3">
                                                                                Continue <i class="bi bi-arrow-right ms-1"></i>
                                                                            </a>
                                                                        </div>
                                                                    </ul>
                                                                @else
                                                                    <p class="text-muted">No content available for this lesson.</p>
                                                                @endif
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Right Side: Quiz -->

                                    <div class="col-md-8 col-lg-9">
                                        <div class="card shadow-sm mb-3">
                                            <div class="card-header">
                                                <h5 class="card-title mb-2" style="text-align: center; margin-right: 100px; font-weight: bold;" >Practice Quiz: {{ $course->title }} </h5>
                                            </div>
                                            <div class="card-body">
                                                <!-- Quiz Title -->
                                                <div class="text-center mb-3">
                                                    <p class="text-muted">Test your knowledge with this interactive quiz</p>
                                                </div>

                                                <!-- Quiz Box -->
                                                <div class="p-4 bg-light rounded text-center">
                                                    <div class="mb-3">
                                                        <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #6c757d;"></i>
                                                    </div>
                                                    <h6 class="fw-bold">Ready to start the quiz?</h6>
                                                    <p class="text-muted">
                                                        You have 3 attempts to pass this quiz. Take your time and review the material if needed.
                                                    </p>
                                                    <form action="{{ route('quiz.start', $course->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-dark px-4" style="color:#fff;">
                                                            Start Quiz
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
@endsection
