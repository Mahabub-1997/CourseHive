@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <!-- Page Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">All Share Experiences</h1>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card">
            <div class="card-body">

                {{-- Flash Message --}}
                @if(Session::get('message'))
                    <div class="alert alert-success alert-dismissible col-md-5">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> {{ Session::get('message') }}</h5>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-gradient-teal text-white">
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Review</th>
                            <th>Course</th>
                            <th>Rating</th>
{{--                            <th class="text-center">Actions</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($data as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ Str::limit($item->description, 50) ?? '-' }}</td>
                                <td>{{ $item->onlineCourse->title ?? '-' }}</td>
                                <td>{{ $item->rating->rating_point ?? '-' }}</td>
{{--                                <td class="text-center">--}}
{{--                                    <a href="{{ route('share.experiance.edit', $item->id) }}" class="btn btn-info btn-sm">--}}
{{--                                        <i class="fa fa-edit"></i>--}}
{{--                                    </a>--}}
{{--                                    <form action="{{ route('share.experiance.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this record?');">--}}
{{--                                        @csrf--}}
{{--                                        @method('DELETE')--}}
{{--                                        <button type="submit" class="btn btn-danger btn-sm">--}}
{{--                                            <i class="fas fa-trash-alt"></i>--}}
{{--                                        </button>--}}
{{--                                    </form>--}}
{{--                                </td>--}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No Share Experience records found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination (if using paginate() in controller) --}}
                    {{-- {{ $data->links() }} --}}
                </div>
            </div>
        </div>
    </div>
@endsection
