@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">

        <!-- ================== Page Header ================== -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Add Instructor</h1>
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
                <form action="{{ route('instructors.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Course -->
                        <div class="col-md-6 mb-3">
                            <label for="course_id" class="form-label fw-bold">Course <span class="text-danger">*</span></label>
                            <select name="course_id" id="course_id" class="form-control @error('course_id') is-invalid @enderror">
                                <option value="">-- Select Course --</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
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
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Enter instructor name">
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label fw-bold">Image</label>
                            <input type="file" name="image" id="image"
                                   class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Rating -->
                        <div class="col-md-6 mb-3">
                            <label for="rating" class="form-label fw-bold">Rating</label>
                            <input type="number" step="0.01" name="rating" id="rating"
                                   value="{{ old('rating') }}"
                                   class="form-control @error('rating') is-invalid @enderror"
                                   placeholder="0 - 5">
                            @error('rating')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Total Lessons -->
                        <div class="col-md-6 mb-3">
                            <label for="total_lesson" class="form-label fw-bold">Total Lessons <span class="text-danger">*</span></label>
                            <input type="number" name="total_lesson" id="total_lesson"
                                   value="{{ old('total_lesson') }}"
                                   class="form-control @error('total_lesson') is-invalid @enderror"
                                   placeholder="Enter total lessons">
                            @error('total_lesson')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-bold">Description</label>
                            <textarea name="description" id="description" rows="4"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Enter instructor details">{{ old('description') }}</textarea>
                            @error('description')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-teal text-white me-2">
                            <i class="fa fa-save"></i> Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
