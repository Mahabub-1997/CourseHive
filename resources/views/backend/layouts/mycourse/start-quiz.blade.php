
@extends('backend.partials.master')

@section('content')
    <div class="container py-5">

        <!-- Quiz Header -->
        <div class="text-center mb-5">
            <h2 class="fw-bold text-primary">{{ $quiz->title ?? 'Quiz' }}</h2>
            <p class="text-muted">Choose the correct answer for each question and review before submitting.</p>
        </div>

        <!-- Quiz Form -->
        <form action="{{ route('quiz.review', $quiz->id) }}" method="POST">
            @csrf

            @foreach($quiz->questions as $question)
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">

                        <!-- Question -->
                        <p class="fw-semibold mb-3 fs-5">
                            <span class="badge bg-primary me-2">{{ $loop->iteration }}</span>
                            {!! $question->question_text !!}
                        </p>

                        <!-- Options -->
                        <div class="row">
                            @foreach($question->options as $option)
                                <div class="col-md-6 mb-3">
                                    <div class="form-check p-3 border rounded hover-effect h-100">
                                        <input class="form-check-input" type="radio"
                                               name="answers[{{ $question->id }}]"
                                               id="opt-{{ $option->id }}"
                                               value="{{ $option->id }}">
                                        <label class="form-check-label ms-2 d-block" for="opt-{{ $option->id }}">
                                            {!! is_array($option->option_text)
                                                ? ($option->option_text['text'] ?? json_encode($option->option_text))
                                                : $option->option_text !!}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Submit Button -->
            <div class="text-center mt-4">
                <button type="submit" class="btn btn-lg btn-primary px-5 shadow-sm">
                    <i class="fa fa-check-circle me-2"></i> Review Answers
                </button>
            </div>
        </form>
    </div>

    {{-- Extra CSS --}}
    <style>
        .hover-effect {
            transition: 0.3s;
        }
        .hover-effect:hover {
            background-color: #f8f9fa;
            border-color: #0d6efd;
            cursor: pointer;
        }
        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
@endsection

