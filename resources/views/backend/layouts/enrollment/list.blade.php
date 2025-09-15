
@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <!-- Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12 align-items-center"></div>
                    <div class="col-sm-6">
                        <h1 class="m-0">
                            @if(isset($course) && $course)
                                Users Enrolled in: {{ $course->title }}
                            @else
                                All Enrollments
                            @endif
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Cards --}}
        <div class="container-fluid mb-4">
            <div class="row mt-4">
                <!-- Total Enrollments -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Total Enrollments</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">{{ $enrollments->count() }}</span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                            <i class="fas fa-users" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                </div>

                <!-- Active Enrollments -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Suspend</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">
                                {{ $enrollments->where('status', 'failed')->count() }}
                            </span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                            <i class="fas fa-check-circle" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                </div>

                <!-- Completed Enrollments -->
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="info-box mb-3 d-flex justify-content-between align-items-center">
                        <div class="info-box-content">
                            <span class="info-box-text fw-bold" style="font-size: 1.5rem;">Completed</span>
                            <span class="info-box-number text-primary" style="font-size: 2rem;">
                                {{ $enrollments->where('status', 'success')->count() }}
                            </span>
                        </div>
                        <span class="info-box-icon bg-white elevation-1 text-dark">
                            <i class="fas fa-graduation-cap" style="font-size: 2rem;"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="card">
            <div class="card-body">
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
                            <th>User Email</th>
                            @if(!(isset($course) && $course))
                                <th>Course Title</th>
                            @endif
                            <th>Status</th>
                            <th>Enrolled At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($enrollments as $enrollment)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $enrollment->user->name ?? 'N/A' }}</td>
                                <td>{{ $enrollment->user->email ?? 'N/A' }}</td>
                                @if(!(isset($course) && $course))
                                    <td>{{ $enrollment->course->title ?? 'N/A' }}</td>
                                @endif
                                <td>
                                    <form action="{{ route('enrollments.update-status', $enrollment->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" onchange="this.form.submit()" class="form-control form-control-sm">
                                            <option value="pending" {{ $enrollment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="success" {{ $enrollment->status == 'success' ? 'selected' : '' }}>Success</option>
                                            <option value="failed" {{ $enrollment->status == 'failed' ? 'selected' : '' }}>Failed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>{{ $enrollment->enrolled_at ?? $enrollment->created_at }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No enrollments found.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination if you used paginate() --}}
                    @if(method_exists($enrollments, 'links'))
                        {{ $enrollments->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
