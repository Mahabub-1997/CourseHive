@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">

        <!-- ================== Page Header ================== -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Instructor</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('instructors.index') }}" class="btn bg-gradient-secondary btn-sm">
                                <i class="fa fa-arrow-left text-light"></i> Back to List
                            </a>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================== Form Section ================== -->
        <div class="card">
            <div class="card-body">
                <form action="{{ route('instructors.update', $instructor->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Course -->
                        <div class="col-md-6 mb-3">
                            <label for="course_id" class="form-label fw-bold">Course <span class="text-danger">*</span></label>
                            <select name="course_id" id="course_id" class="form-control @error('course_id') is-invalid @enderror">
                                <option value="">-- Select Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ $instructor->course_id == $course->id ? 'selected' : '' }}>
                                        {{ $course->title }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', $instructor->name) }}"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label fw-bold">Image</label>
                            <input type="file" name="image" id="image"
                                   class="form-control @error('image') is-invalid @enderror">
                            @if($instructor->image)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$instructor->image) }}" width="80" height="80" class="rounded">
                                </div>
                            @endif
                            @error('image')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Rating -->
                        <div class="col-md-6 mb-3">
                            <label for="rating" class="form-label fw-bold">Rating</label>
                            <input type="number" step="0.01" name="rating" id="rating"
                                   value="{{ old('rating', $instructor->rating) }}"
                                   class="form-control @error('rating') is-invalid @enderror">
                            @error('rating')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Total Lessons -->
                        <div class="col-md-6 mb-3">
                            <label for="total_lesson" class="form-label fw-bold">Total Lessons <span class="text-danger">*</span></label>
                            <input type="number" name="total_lesson" id="total_lesson"
                                   value="{{ old('total_lesson', $instructor->total_lesson) }}"
                                   class="form-control @error('total_lesson') is-invalid @enderror">
                            @error('total_lesson')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="form-control @error('description') is-invalid @enderror">{{ old('description', $instructor->description) }}</textarea>
                            @error('description')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-teal text-white me-2">
                            <i class="fa fa-save"></i> Update
                        </button>
                        <a href="{{ route('instructors.index') }}" class="btn bg-gradient-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
