@extends('backend.partials.master')
@section('content')
    <div class="content-wrapper">
        <div class="col-md-8 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Add Course') }}</div>
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
                    <form action="{{ route('web-online-courses.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        {{-- Title --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Title <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <input type="text" required class="form-control" name="title" value="{{ old('title') }}">
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Description</label>
                            <div class="col-md-9">
                                <textarea class="form-control" name="description" rows="4">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Price</label>
                            <div class="col-md-9">
                                <input type="number" step="0.01" class="form-control" name="price" value="{{ old('price') }}">
                            </div>
                        </div>

                        {{-- Level --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Level</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="level" value="{{ old('level') }}">
                            </div>
                        </div>

                        {{-- Duration --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Duration</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="duration" value="{{ old('duration') }}">
                            </div>
                        </div>

                        {{-- Language --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Language</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="language" value="{{ old('language') }}">
                            </div>
                        </div>

                        {{-- Image --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Image <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <input type="file" required class="form-control" name="image" style="height:45px; padding:6px;">
                            </div>
                        </div>
                        {{-- Course Type --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Course Type</label>
                            <div class="col-md-9">
                                <select name="course_type" class="form-control">
                                    <option value="free" {{ old('course_type') == 'free' ? 'selected' : '' }}>Free</option>
                                    <option value="paid" {{ old('course_type') == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>
                        </div>

{{--                        --}}{{-- User --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">User</label>
                            <div class="col-md-9">
                                <select name="user_id" class="form-control">
                                    <option value="">-- Select User --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Rating --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Rating</label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="rating_id" value="{{ old('rating_id') }}">
                            </div>
                        </div>

{{--                        --}}{{-- Category --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Category</label>
                            <div class="col-md-9">
                                <select name="category_id" class="form-control">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-primary" value="Add Course">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
