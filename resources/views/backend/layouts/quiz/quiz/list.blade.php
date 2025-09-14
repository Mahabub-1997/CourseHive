@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid mb-4">
            <div class="row" style="margin-top: 20px;">
                <div class="col-12">
                    <div class="container-fluid mb-4">
                        <div class="row" style="margin-top: 80px;"> <!-- moved down by 80px -->
                            <div class="col-12">
                                <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                                    <div class="info-box-content">
                                        <span class="info-box-text fw-bold" style="font-size: 2rem;">My Quizzes</span>
                                        <span class="info-box-number" style="font-size: .7rem;">
                        You're making great progress through your quizzes
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
                                    <h1 class="m-0">Quizzes</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <a href="{{ route('quizzes.create') }}" class="btn bg-gradient-teal btn-sm">
                                            <i class="fa fa-plus text-light"></i> Add New Quiz
                                        </a>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-gradient-teal text-white">
                                    <tr>
                                        <th>#</th>
                                        <th>Part</th>
                                        <th>Title</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($quizzes as $quiz)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $quiz->part->title ?? 'N/A' }}</td>
                                            <td>{{ $quiz->title }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('quizzes.edit', $quiz->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('quizzes.destroy', $quiz->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this quiz?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No quizzes found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>

                                {{ $quizzes->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
