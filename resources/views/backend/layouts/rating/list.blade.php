@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <!-- Main Card -->
        <div class="card shadow-sm">
            <div class="card-body">

                <!-- Flash Message -->
                @if(Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show col-md-5">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5 class="mb-0">
                            <i class="icon fas fa-check"></i> {{ Session::get('success') }}
                        </h5>
                    </div>
                @endif

                <div class="content-header">
                    <div class="container-fluid">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1 class="m-0">All Ratings</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-right">
                                    <a href="{{ route('web.ratings.create') }}" class="btn bg-gradient-teal btn-sm">
                                        <i class="fa fa-plus text-light"></i> Add New Rating
                                    </a>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="bg-gradient-teal text-white">
                        <tr>
                            <th>#</th>
                            <th>User Name</th>
                            <th>Course</th>
                            <th>Rating</th>
                            <th>Created At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>

                        <tbody>
                        @forelse($ratings as $rating)
                            <tr>
                                <!-- Loop iteration -->
                                <td>{{ $loop->iteration }}</td>

                                <!-- User Name -->
                                <td>{{ $rating->user->name ?? 'N/A' }}</td>

                                <!-- Course -->
                                <td>{{ $rating->course->title ?? '-' }}</td>

                                <!-- Rating -->
                                <td>
                                    @if($rating->rating_point)
                                        <span class="badge bg-warning text-dark">
                                        {{ $rating->rating_point }} ★
                                    </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <!-- Created At -->
                                <td>{{ $rating->created_at->format('d M Y') }}</td>

                                <!-- Actions -->
                                <td class="text-center">
                                    <a href="{{ route('web.ratings.edit', $rating->id) }}"
                                       class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('web.ratings.destroy', $rating->id) }}"
                                          method="POST"
                                          style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <!-- Empty State -->
                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    No Ratings found
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-3">
                    {{ $ratings->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
