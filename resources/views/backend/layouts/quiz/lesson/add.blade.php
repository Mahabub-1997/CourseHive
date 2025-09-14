@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-7 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Add Lesson') }}</div>
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
                    <form action="{{ route('lessons.store') }}" method="post">
                        @csrf

                        {{-- Course Dropdown --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Course <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="course_id" class="form-control" required>
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Title --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Title <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Enter lesson title">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" class="form-control" placeholder="Enter lesson description">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-success" value="Save Lesson">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

