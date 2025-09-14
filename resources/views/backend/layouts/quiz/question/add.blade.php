@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-7 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Add Question') }}</div>
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
                    <form action="{{ route('questions.store') }}" method="post">
                        @csrf

                        {{-- Quiz Dropdown --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Quiz <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="quiz_id" class="form-control" required>
                                    <option value="">Select Quiz</option>
                                    @foreach($quizzes as $quiz)
                                        <option value="{{ $quiz->id }}" {{ old('quiz_id') == $quiz->id ? 'selected' : '' }}>
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
                                <textarea name="question_text" class="form-control" required placeholder="Enter question text">{{ old('question_text') }}</textarea>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-success" value="Save Question">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
