
@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-7 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Edit Part') }}</div>
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
                    <form action="{{ route('web-parts.update', $part->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Lesson Dropdown --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Lesson <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="lesson_id" class="form-control" required>
                                    <option value="">Select Lesson</option>
                                    @foreach($lessons as $lesson)
                                        <option value="{{ $lesson->id }}" {{ $part->lesson_id == $lesson->id ? 'selected' : '' }}>
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
                                <input type="text" name="title" class="form-control"
                                       value="{{ old('title', $part->title) }}" required
                                       placeholder="Enter part title">
                            </div>
                        </div>

                        {{-- Video Upload --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Video</label>
                            <div class="col-md-9">
                                @if($part->video)
                                    {{-- Preview existing video --}}
                                    <div class="mb-2">
                                        <video width="250" controls>
                                            <source src="{{ asset('storage/' . $part->video) }}" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>
                                    <small class="text-muted d-block mb-2">Uploading a new file will replace the current video.</small>
                                @endif
                                <input type="file" name="video" class="form-control" accept="video/*">
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Content</label>
                            <div class="col-md-9">
                                <textarea name="content" class="form-control"
                                          placeholder="Enter part content">{{ old('content', $part->content) }}</textarea>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-success" value="Update Part">
                                <a href="{{ route('web-parts.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
