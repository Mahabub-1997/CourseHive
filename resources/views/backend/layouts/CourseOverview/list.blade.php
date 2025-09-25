@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">

        <!-- ================== Page Header ================== -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Course Overview</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('web-overview.create') }}" class="btn bg-gradient-teal btn-sm">
                                <i class="fa fa-plus text-light"></i> Add Course Overview
                            </a>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================== Table Section ================== -->
        <div class="card">

            {{-- Success Message --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible col-md-5 mt-3 ms-3">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h5><i class="icon fas fa-check"></i> {{ session('success') }}</h5>
                </div>
            @endif

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-gradient-teal text-white">
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>Course</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Created By</th>
                            <th class="text-center" style="width: 180px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($learns as $learn)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $learn->course->title ?? '-' }}</td>
                                <td>{{ $learn->title }}</td>
                                <td>
                                    @if(is_array($learn->description))
                                        <ul class="mb-0">
                                            @foreach($learn->description as $desc)
                                                <li>{{ $desc }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ $learn->description }}
                                    @endif
                                </td>
                                <td>{{ $learn->user->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('web-overview.edit', $learn->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('web-overview.destroy', $learn->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
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
                                <td colspan="6" class="text-center">No records found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $learns->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
