@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-8 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">Edit Course</div>
                <div class="card-body">

                    @if ($errors->any())
                        <div style="color:red;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('web-online-courses.update', $online_course) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Title *</label>
                            <div class="col-md-9">
                                <input type="text" name="title" required class="form-control" value="{{ old('title', $online_course->title) }}">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" class="form-control" rows="4">{{ old('description', $online_course->description) }}</textarea>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Price</label>
                            <div class="col-md-9">
                                <input type="number" name="price" class="form-control" step="0.01" value="{{ old('price', $online_course->price) }}">
                            </div>
                        </div>

                        {{-- Course Type --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Course Type</label>
                            <div class="col-md-9">
                                <select name="course_type" class="form-control" required>
                                    <option value="free" {{ old('course_type', $online_course->course_type) == 'free' ? 'selected' : '' }}>Free</option>
                                    <option value="paid" {{ old('course_type', $online_course->course_type) == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>
                        </div>

                        {{-- Level, Duration, Language --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Level</label>
                            <div class="col-md-9"><input type="text" name="level" class="form-control" value="{{ old('level', $online_course->level) }}"></div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Duration</label>
                            <div class="col-md-9"><input type="text" name="duration" class="form-control" value="{{ old('duration', $online_course->duration) }}"></div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Language</label>
                            <div class="col-md-9"><input type="text" name="language" class="form-control" value="{{ old('language', $online_course->language) }}"></div>
                        </div>

                        {{-- Image --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Image</label>
                            <div class="col-md-9">
                                <input type="file" name="image" class="form-control">
                                @if($online_course->image)
                                    <img src="{{ asset('uploads/courses/' . $online_course->image) }}" width="100" class="mt-2" alt="Course Image">
                                @endif
                            </div>
                        </div>

                        {{-- User --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">User</label>
                            <div class="col-md-9">
                                <select name="user_id" class="form-control">
                                    <option value="">-- Select User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id', $online_course->user_id) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Rating --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Rating</label>
                            <div class="col-md-9"><input type="number" name="rating_id" class="form-control" value="{{ old('rating_id', $online_course->rating_id) }}"></div>
                        </div>

                        {{-- Category --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Category</label>
                            <div class="col-md-9">
                                <select name="category_id" class="form-control" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $online_course->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-primary" value="Update Course">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
