
@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="container py-5">

            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold">Quiz Result: {{ $quiz->title ?? 'Untitled Quiz' }}</h3>
                <span class="badge {{ $isPassed ? 'bg-success' : 'bg-danger' }} p-2 fs-6">
                {{ $isPassed ? 'Passed' : 'Failed' }}
            </span>
            </div>

            <!-- Score Summary -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h4 class="fw-bold">{{ $score }}/{{ $totalQuestions }}</h4>
                            <p class="mb-0 text-muted">Score</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h4 class="fw-bold">{{ $percentage }}%</h4>
                            <p class="mb-0 text-muted">Percentage</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h4 class="fw-bold">{{ $attemptNumber ?? 1 }}</h4>
                            <p class="mb-0 text-muted">Attempt</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card shadow-sm text-center">
                        <div class="card-body">
                            <h4 class="fw-bold">
                            <span class="{{ $isPassed ? 'text-success' : 'text-danger' }}">
                                {{ $isPassed ? '✔' : '✘' }}
                            </span>
                            </h4>
                            <p class="mb-0 text-muted">Status</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Breakdown -->
            <h4 class="fw-bold mb-3">Question Breakdown</h4>
            @foreach($results as $r)
                <div class="card shadow-sm mb-3">
                    <div class="card-body">
                        <p class="fw-bold mb-2">{{ $loop->iteration }}. {!! $r['question'] !!}</p>
                        <p class="mb-1">
                            <strong>Your Answer:</strong>
                            {!! is_array($r['user_answer'])
                                ? implode(', ', $r['user_answer'])
                                : ($r['user_answer'] ?? '<em class="text-muted">Not answered</em>') !!}
                        </p>
                        <p class="mb-1">
                            <strong>Correct Answer:</strong>
                            {!! is_array($r['correct_answer'])
                                ? implode(', ', $r['correct_answer'])
                                : ($r['correct_answer'] ?? '<em class="text-muted">N/A</em>') !!}
                        </p>
                        <p class="mb-0">
                            <strong>Status:</strong>
                            {!! $r['is_correct']
                                ? '<span class="badge bg-success">Correct</span>'
                                : '<span class="badge bg-danger">Wrong</span>' !!}
                        </p>
                    </div>
                </div>
            @endforeach

            <!-- Action buttons -->
            <div class="mt-4 d-flex justify-content-between">
                <a href="{{ route('quiz.start', $quiz->id) }}" class="btn btn-primary">
                    <i class="fa fa-redo"></i> Retake Quiz
                </a>
                <a href="{{ route('courses.in-progress') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to My Courses
                </a>
            </div>

        </div>
    </div>
@endsection
