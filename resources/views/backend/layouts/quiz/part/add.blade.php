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

                    <form action="{{ route('web-parts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        {{-- Lesson Dropdown --}}
                        <div class="form-group">
                            <label for="lesson_id">Lesson</label>
                            <select name="lesson_id" class="form-control" required>
                                @foreach($lessons as $lesson)
                                    <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Title --}}
                        <div class="form-group">
                            <label for="title">Part Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        {{-- Video Upload --}}
                        <div class="form-group">
                            <label for="video">Upload Video</label>
                            <input type="file" name="video" class="form-control" accept="video/*">
                        </div>

                        {{-- Content --}}
                        <div class="form-group">
                            <label for="content">Content</label>
                            <textarea name="content" class="form-control"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Save Part</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

