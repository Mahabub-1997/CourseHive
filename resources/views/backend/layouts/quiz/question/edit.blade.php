@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        {{-- Info Box --}}
        <div class="container-fluid mb-4">
            <div class="row" style="margin-top: 80px;">
                <div class="col-12">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 2rem;">Edit Question</span>
                            <span class="info-box-number" style="font-size: .7rem;">
                            Update your question carefully
                        </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="col-md-7 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Edit Question') }}</div>
                <div class="card-body">

                    {{-- Show Validation Errors --}}
                    @if ($errors->any())
                        <div style="color: red;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('questions.update', $question->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Quiz Dropdown --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Quiz <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="quiz_id" class="form-control" required>
                                    <option value="">Select Quiz</option>
                                    @foreach($quizzes as $quiz)
                                        <option value="{{ $quiz->id }}" {{ $question->quiz_id == $quiz->id ? 'selected' : '' }}>
                                            {{ $quiz->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Question Text --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Question <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <textarea name="question_text" class="form-control" required placeholder="Enter question text">{{ old('question_text', $question->question_text) }}</textarea>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-success" value="Update Question">
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
