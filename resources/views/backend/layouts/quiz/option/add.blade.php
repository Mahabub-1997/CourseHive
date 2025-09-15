@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-7 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Add Option') }}</div>
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
                    <form action="{{ route('options.store') }}" method="post">
                        @csrf

                        {{-- Question Dropdown --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Question <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="question_id" class="form-control" required>
                                    <option value="">Select Question</option>
                                    @foreach($questions as $question)
                                        <option value="{{ $question->id }}" {{ old('question_id') == $question->id ? 'selected' : '' }}>
                                            {{ $question->question_text }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Option Text --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Option Text <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <input type="text" name="option_text" class="form-control" value="{{ old('option_text') }}" required placeholder="Enter option text">
                            </div>
                        </div>

                        {{-- Is Correct --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Is Correct <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="is_correct" class="form-control" required>
                                    <option value="0" {{ old('is_correct') == '0' ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ old('is_correct') == '1' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-success" value="Save Option">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

