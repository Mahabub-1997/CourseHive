@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">

        <!-- ================== Page Header ================== -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Instructor List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('instructors.create') }}" class="btn bg-gradient-teal btn-sm">
                                <i class="fa fa-plus text-light"></i> Add Instructor
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
                            <th>Name</th>
                            <th>Rating</th>
                            <th>Total Lessons</th>
                            <th>Created By</th>
                            <th class="text-center" style="width: 180px">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($instructors as $inst)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $inst->course->title ?? '-' }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($inst->image)
                                            <img src="{{ asset('storage/'.$inst->image) }}" alt="Instructor"
                                                 class="rounded-circle me-2" width="40" height="40">
                                        @endif
                                        {{ $inst->name }}
                                    </div>
                                </td>
                                <td>{{ number_format($inst->rating, 2) }}</td>
                                <td>{{ $inst->total_lesson }}</td>
                                <td>{{ $inst->user->name ?? 'N/A' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('instructors.edit', $inst->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('instructors.destroy', $inst->id) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this instructor?');">
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
                                <td colspan="7" class="text-center">No instructors found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $instructors->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
