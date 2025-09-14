@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        {{-- Info Box --}}
        <div class="container-fluid mb-4">
            <div class="row" style="margin-top: 80px;">
                <div class="col-12">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 2rem;">Edit Quiz</span>
                            <span class="info-box-number" style="font-size: .7rem;">
                                Update quiz details
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Form Section --}}
        <div class="col-md-7 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Edit Quiz') }}</div>
                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div style="color: red;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Edit Form --}}
                    <form action="{{ route('quizzes.update', $quiz->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Lesson Dropdown --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Lesson <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="part_id" class="form-control" required>
                                    <option value="">Select Part</option>
                                    @foreach($parts as $part)
                                        <option value="{{ $part->id }}" {{ $quiz->part_id == $part->id ? 'selected' : '' }}>
                                            {{ $part->title }} (Lesson: {{ $part->lesson->title ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Quiz Title --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Quiz Title <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control" value="{{ old('title', $quiz->title) }}" required placeholder="Enter quiz title">
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-success" value="Update Quiz">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
