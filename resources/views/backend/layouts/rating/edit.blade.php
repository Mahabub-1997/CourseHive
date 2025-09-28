@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-7 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Edit Rating') }}</div>
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
                    <form action="{{ route('web.ratings.update', $rating->id) }}" method="post">
                        @csrf
                        @method('PUT') {{-- Important for update --}}

                        {{-- User Dropdown --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">User <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="user_id" class="form-control" required>
                                    <option value="">Select User</option>
                                    @foreach($users as $id => $name)
                                        <option value="{{ $id }}" {{ ($rating->user_id == $id || old('user_id') == $id) ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Course Dropdown --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Course <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="course_id" class="form-control" required>
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ ($rating->course_id == $course->id || old('course_id') == $course->id) ? 'selected' : '' }}>
                                            {{ $course->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Rating Point --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Rating <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <input type="number" name="rating_point" class="form-control"
                                       min="1" max="5"
                                       value="{{ old('rating_point', $rating->rating_point) }}"
                                       placeholder="Enter rating (1 to 5)" required>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-success" value="Update Rating">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
