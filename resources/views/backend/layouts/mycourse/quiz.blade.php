
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
        <div class="container-fluid mb-4">

            <div class="row mt-4">
                <!-- Left Sidebar: Course Content -->
                <div class="col-md-4 col-lg-3">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h5 class="card-title mb-1">Course Content</h5>
                            <small>{{ $completedParts }}/{{ $totalParts }} videos completed</small>
                            <div class="progress mt-2" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{ $totalParts ? ($completedParts/$totalParts)*100 : 0 }}%;"
                                     aria-valuenow="{{ $totalParts ? ($completedParts/$totalParts)*100 : 0 }}"
                                     aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="card-body">
                            <ul class="list-unstyled">
                                @foreach($lessons as $lesson)
                                    <li class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center lesson-toggle"
                                             data-bs-toggle="collapse"
                                             data-bs-target="#lesson{{ $lesson->id }}"
                                             style="cursor: pointer; padding:8px 12px; background:#f8f9fa; border-radius:6px;">
                                            <span class="fw-semibold">{{ $lesson->title }}</span>
                                            <i class="bi bi-chevron-down"></i>
                                        </div>

                                        <div id="lesson{{ $lesson->id }}" class="collapse ps-3 mt-2">
                                            @if($lesson->parts->count())
                                                <ul class="list-unstyled">
                                                    @foreach($lesson->parts as $part)
                                                        <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                            <div>
                                                                <i class="bi bi-play-circle me-2 text-primary"></i>
                                                                {{ $part->title }}
                                                                <small class="text-muted">• {{ $part->duration ?? '00:00' }}</small>
                                                            </div>
                                                            <div>
                                                                @if($part->video)
                                                                    <a href="{{ $part->video }}" target="_blank"
                                                                       class="text-decoration-none text-primary">
                                                                        <i class="bi bi-camera-video me-1"></i> Watch
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </li>

                                                        <!-- Quiz Button -->
                                                        @if($part->quiz)
                                                            <div class="mt-2 d-flex justify-content-between align-items-center">
                                                                <div class="badge bg-success">
                                                                    <i class="bi bi-question-circle me-1"></i> Quiz
                                                                </div>

                                                                <form action="{{ route('quiz.start', $part->id) }}" method="POST">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-primary btn-sm px-3">
                                                                        Start Quiz
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        @endif
                                                    @endforeach
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

                <!-- Right Side: Quiz Panel -->
                <div class="col-md-8 col-lg-9">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header">
                            <h5 class="card-title mb-2 text-center fw-bold">Practice Quiz: {{ $course->title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                <p class="text-muted">Test your knowledge with this interactive quiz</p>
                            </div>

                            <div class="p-4 bg-light rounded text-center">
                                <div class="mb-3">
                                    <i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #6c757d;"></i>
                                </div>

                                @if($currentPart && $quiz)
                                    <h6 class="fw-bold">Quiz for: {{ $currentPart->title }}</h6>
                                    <p class="text-muted">
                                        You have 3 attempts to pass this quiz. Take your time and review the material if needed.
                                    </p>
                                    <a href="{{ route('quiz.start', $currentPart->id) }}" class="btn btn-dark px-4">
                                        Continue Quiz
                                    </a>
                                @else
                                    <h6 class="fw-bold">Ready to start the quiz?</h6>
                                    <p class="text-muted">Select a part from the left to start its quiz.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

