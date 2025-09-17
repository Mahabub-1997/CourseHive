@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid mb-4">
            <div class="row" style="margin-top: 80px;"> <!-- moved down by 80px -->
                <div class="col-12">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 2rem;">My Course Content</span>
                            <span class="info-box-number" style="font-size: .7rem;">
                        This course content helps students learn step by step effectively
                    </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Course Content</h1>
                    </div>
                </div>
            </div>
        </div>


        {{-- Lessons Table --}}
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
                            <!-- Right Side: Video + Description -->
                            <div class="col-md-8 col-lg-9">
                                <div class="card shadow-sm mb-3">
                                    <div class="ratio ratio-16x9">
                                        <video controls style="width:1172px; height:450px; object-fit:cover;">
                                            <source src="{{ asset('storage/' . ltrim($currentPart->video, '/')) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>

                                    <div class="card-body">
                                        <h5 class="card-title">Course : {{ $currentPart->title ?? $course->title }}</h5>
                                        <p class="text-muted"> {{ $course->name }} | Instructor: {{ $course->created_by ?? '' }}</p>
                                        <div class="d-flex justify-content-between">
                                            {{-- Previous Lesson (optional) --}}
                                            <a href="{{ route('course.content', ['id' => $course->id, 'partId' => $previousPart->id ?? null]) }}"
                                               class="btn btn-outline-secondary btn-sm {{ empty($previousPart) ? 'disabled' : '' }}">
                                                Previous Lessons
                                            </a>

                                            {{-- Next Lesson --}}
                                            @if($nextPart)
                                                <a href="{{ route('course.content', ['id' => $course->id, 'partId' => $nextPart->id]) }}"
                                                   class="btn btn-primary btn-sm">
                                                    Next Lessons
                                                </a>
                                            @else
                                                <button class="btn btn-primary btn-sm" disabled>Next Lessons</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabs (Video Notes + Resources) -->
                                <div class="card shadow-sm">
                                    <div class="card-header bg-white border-bottom">
                                        <ul class="nav nav-tabs card-header-tabs" id="courseTabs">
                                            <li class="nav-item">
                                                <a class="nav-link active" data-bs-toggle="tab" href="#notes">Video Notes</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" data-bs-toggle="tab" href="#resources">Resources</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="card-body tab-content">
                                        <div class="tab-pane fade show active" id="notes">
                                            <p>{{ $currentPart->notes ?? 'No notes available.' }}</p>
                                        </div>
                                        <div class="tab-pane fade" id="resources">
                                            <p>{{ $currentPart->resources ?? 'No resources available.' }}</p>
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
