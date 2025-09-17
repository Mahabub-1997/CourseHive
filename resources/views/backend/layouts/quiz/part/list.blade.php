@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">

        {{-- Top Info Box --}}
        <div class="container-fluid mb-4">
            <div class="row" style="margin-top: 80px;">
                <div class="col-12">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 2rem;">My Parts</span>
                            <span class="info-box-number" style="font-size: .7rem;">
                                Manage your lesson parts and videos easily
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Header with Add Button --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Parts</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('parts.create') }}" class="btn bg-gradient-teal btn-sm">
                                <i class="fa fa-plus text-light"></i> Add New Part
                            </a>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        {{-- Parts Table --}}
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
                            <th>Lesson</th>
                            <th>Title</th>
                            <th>Video</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($parts as $part)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $part->lesson->title ?? 'N/A' }}</td>
                                <td>{{ $part->title }}</td>
{{--                                <td>--}}
{{--                                    @if($part->video)--}}
{{--                                        <a href="{{ $part->video }}" target="_blank">View Video</a>--}}
{{--                                    @else--}}
{{--                                        N/A--}}
{{--                                    @endif--}}
{{--                                </td>--}}
                                <td>
                                    @if($part->video)
                                        @if(Str::contains($part->video, ['youtube.com', 'youtu.be', 'vimeo.com']))
                                            {{-- External video link --}}
                                            <a href="{{ $part->video }}" target="_blank">View Video</a>
                                        @else
                                            {{-- Uploaded file preview --}}
                                            <video width="200" controls>
                                                <source src="{{ asset('storage/' . $part->video) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('parts.edit', $part->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('parts.destroy', $part->id) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this part?');">
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
                                <td colspan="5" class="text-center">No parts found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{ $parts->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
