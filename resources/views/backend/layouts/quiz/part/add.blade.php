@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-7 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Add Part') }}</div>
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
                    <form action="{{ route('parts.store') }}" method="post">
                        @csrf

                        {{-- Lesson Dropdown --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Lesson <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="lesson_id" class="form-control" required>
                                    <option value="">Select Lesson</option>
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}" {{ old('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                            {{ $lesson->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Title --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Title <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Enter part title">
                            </div>
                        </div>

                        {{-- Video --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Video</label>
                            <div class="col-md-9">
                                <input type="text" name="video" class="form-control" value="{{ old('video') }}" placeholder="Enter video link (optional)">
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Content</label>
                            <div class="col-md-9">
                                <textarea name="content" class="form-control" placeholder="Enter part content">{{ old('content') }}</textarea>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-success" value="Save Part">
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

