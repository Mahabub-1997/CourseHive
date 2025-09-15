@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">

        <div class="container-fluid mb-4">
            <div class="row" style="margin-top: 80px;"> <!-- moved down by 80px -->
                <div class="col-12">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 2rem;">My Lessons</span>
                            <span class="info-box-number" style="font-size: .7rem;">
                        You're making great progress through your lessons
                    </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Lessons</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('lessons.create') }}" class="btn bg-gradient-teal btn-sm">
                                <i class="fa fa-plus text-light"></i> Add New Lesson
                            </a>
                        </ol>
                    </div>
                </div>
            </div>
        </div>


        {{-- Lessons Table --}}
        <div class="card">
            <div class="card-body">
                @if(Session::get('success'))
                    <div class="alert alert-success alert-dismissible col-md-5">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> {{ Session::get('success') }}</h5>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-gradient-teal text-white">
                        <tr>
                            <th>#</th>
                            <th>Course</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($lessons as $lesson)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $lesson->course->title ?? 'N/A' }}</td>
                                <td>{{ $lesson->title }}</td>
                                <td>{{ $lesson->description }}</td>
                                <td class="text-center">
                                    <a href="{{ route('lessons.edit', $lesson->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this lesson?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No lessons found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{ $lessons->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
