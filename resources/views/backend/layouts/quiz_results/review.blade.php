@extends('backend.partials.master')

@section('content')
    <div class="container mt-4">

        <div class="text-center mb-4">
            <h2 class="fw-bold text-primary">Review Your Answers</h2>
            <p class="text-muted">Check your answers below before submitting.</p>
        </div>

        @foreach($results as $r)
            <div class="card shadow-sm border-0 mb-3
            {{ $r['is_correct'] ? 'border-success' : 'border-danger' }}">

                <div class="card-body">
                    <h5 class="fw-semibold">
                        <span class="text-primary me-2">{{ $loop->iteration }}.</span>
                        {!! $r['question'] !!}
                    </h5>

                    <p class="mb-1">
                        <strong>Your Answer:</strong>
                        {!! $r['user_answer'] ?? '<em class="text-muted">Not answered</em>' !!}
                    </p>

                    <p class="mb-1">
                        <strong>Correct Answer:</strong>
                        {!! $r['correct_answer'] ?? '<em class="text-muted">N/A</em>' !!}
                    </p>

                    <p class="mb-0">
                        <strong>Status:</strong>
                        @if($r['is_correct'])
                            <span class="badge bg-success">Correct ✅</span>
                        @else
                            <span class="badge bg-danger">Wrong ❌</span>
                        @endif
                    </p>
                </div>
            </div>
        @endforeach

        <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST" class="mt-4 text-center">
            @csrf

            {{-- Re-send all answers as hidden inputs --}}
            @foreach($answers as $qId => $oId)
                <input type="hidden" name="answers[{{ $qId }}]" value="{{ $oId }}">
            @endforeach

            <a href="javascript:history.back()" class="btn btn-outline-secondary me-2 px-4">
                Edit Answers
            </a>
            <button type="submit"  class="btn btn-success px-4 shadow">
                Confirm & Submit
            </button>
        </form>
    </div>
@endsection
