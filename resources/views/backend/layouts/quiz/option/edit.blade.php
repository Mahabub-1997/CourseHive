@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-10 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Edit Options') }}</div>
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
                    <form action="{{ route('options.update', $question->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Question Dropdown --}}
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label">Question <i class="text-danger">*</i></label>
                            <div class="col-md-10">
                                <select name="question_id" class="form-control" required>
                                    <option value="">Select Question</option>
                                    @foreach($questions as $q)
                                        <option value="{{ $q->id }}" {{ $question->id == $q->id ? 'selected' : '' }}>
                                            {{ $q->question_text }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Multiple Options --}}
                        @foreach ($options as $i => $option)
                            <div class="border p-3 mb-3 rounded">
                                <h6>Option {{ $i+1 }}</h6>

                                {{-- Hidden ID --}}
                                <input type="hidden" name="options[{{ $i }}][id]" value="{{ $option->id }}">

                                {{-- Option Text --}}
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Text</label>
                                    <div class="col-md-10">
                                        <input type="text" name="options[{{ $i }}][en]" class="form-control mb-2"
                                               value="{{ old("options.$i.en", is_array($option->option_text) ? ($option->option_text['en'] ?? '') : $option->option_text) }}"
                                               placeholder="Enter option text">
                                    </div>
                                </div>

                                {{-- Is Correct --}}
                                <div class="form-group row">
                                    <label class="col-md-2 col-form-label">Is Correct?</label>
                                    <div class="col-md-10">
                                        <select name="options[{{ $i }}][is_correct]" class="form-control">
                                            <option value="0" {{ $option->is_correct == 0 ? 'selected' : '' }}>No</option>
                                            <option value="1" {{ $option->is_correct == 1 ? 'selected' : '' }}>Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-10 offset-md-2">
                                <input type="submit" class="btn btn-success" value="Update Options">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
