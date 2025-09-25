@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-8 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">
                    {{ __('Edit Course Overview') }}
                </div>
                <div class="card-body">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('web-overview.update', $learn->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Course --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Select Course <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <select name="course_id" required class="form-control">
                                    <option value="">-- Select Course --</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}"
                                            {{ (old('course_id', $learn->course_id) == $course->id) ? 'selected' : '' }}>
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
                                <input type="text" required class="form-control" name="title"
                                       value="{{ old('title', $learn->title) }}" placeholder="Enter overview title">
                            </div>
                        </div>

                        {{-- Description (JSON Array Input) --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Description</label>
                            <div class="col-md-9">
                                @if(is_array($learn->description))
                                    @foreach($learn->description as $index => $desc)
                                        <textarea name="description[]" class="form-control mb-2"
                                                  placeholder="Enter description point">{{ old("description.$index", $desc) }}</textarea>
                                    @endforeach
                                @else
                                    <textarea name="description[]" class="form-control mb-2"
                                              placeholder="Enter description point">{{ old('description.0', $learn->description) }}</textarea>
                                @endif
                                <div id="extra-descriptions"></div>
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2"
                                        onclick="addDescriptionField()">+ Add More</button>
                                <small class="form-text text-muted">You can add multiple description points.</small>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-primary" value="Update Overview">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script to add multiple description fields --}}
    <script>
        function addDescriptionField() {
            const container = document.getElementById('extra-descriptions');
            const textarea = document.createElement('textarea');
            textarea.classList.add('form-control', 'mb-2');
            textarea.name = 'description[]';
            textarea.placeholder = 'Enter another description point';
            container.appendChild(textarea);
        }
    </script>
@endsection
