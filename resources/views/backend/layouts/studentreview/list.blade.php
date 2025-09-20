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

        <!-- Main Card -->
        <div class="card shadow-sm">
            <div class="card-body">

                <!-- Flash Message -->
                @if(Session::get('message'))
                    <div class="alert alert-success alert-dismissible fade show col-md-5">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5 class="mb-0">
                            <i class="icon fas fa-check"></i> {{ Session::get('message') }}
                        </h5>
                    </div>
                @endif

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="bg-gradient-teal text-white">
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Review</th>
                            <th>Course</th>
                            <th>Rating</th>
                            {{-- <th class="text-center">Actions</th> --}}
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($data as $item)
                            <tr>
                                <!-- Loop iteration -->
                                <td>{{ $loop->iteration }}</td>

                                <!-- User Name -->
                                <td>{{ $item->name }}</td>

                                <!-- Review (limited text) -->
                                <td>{{ Str::limit($item->description, 50) ?? '-' }}</td>

                                <!-- Related Course -->
                                <td>{{ $item->onlineCourse->title ?? '-' }}</td>

                                <!-- Rating -->
                                <td>
                                    @if($item->rating)
                                        <span class="badge bg-warning text-dark">
                                            {{ $item->rating->rating_point }} ★
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <!-- Empty State -->
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No Share Experience records found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $data->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
