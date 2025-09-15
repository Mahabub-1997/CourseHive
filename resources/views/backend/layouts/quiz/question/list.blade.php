@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid mb-4">
            <div class="row" style="margin-top: 20px;">
                <div class="col-12">
                    <div class="container-fluid mb-4">
                        {{-- Info Box --}}
                        <div class="container-fluid mb-4">
                            <div class="row" style="margin-top: 80px;">
                                <div class="col-12">
                                    <div class="info-box mb-3 d-flex justify-content-between align-items-center bg-primary text-white">
                                        <div class="info-box-content">
                                            <span class="info-box-text fw-bold" style="font-size: 2rem;">My Questions</span>
                                            <span class="info-box-number" style="font-size: .7rem;">
                                You're making great progress through your questions
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
                                        <h1 class="m-0">Questions</h1>
                                    </div>
                                    <div class="col-sm-6">
                                        <ol class="breadcrumb float-sm-right">
                                            <a href="{{ route('questions.create') }}" class="btn bg-gradient-teal btn-sm">
                                                <i class="fa fa-plus text-light"></i> Add New Question
                                            </a>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Success Message --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        {{-- Questions Table --}}
                        <div class="card">
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead class="bg-gradient-teal text-white">
                                    <tr>
                                        <th>#</th>
                                        <th>Quiz</th>
                                        <th>Question Text</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($questions as $question)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $question->quiz->title ?? 'N/A' }}</td>
                                            <td>{{ $question->question_text }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('questions.edit', $question->id) }}" class="btn btn-info btn-sm">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <form action="{{ route('questions.destroy', $question->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this question?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No questions found</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>

                                {{ $questions->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
