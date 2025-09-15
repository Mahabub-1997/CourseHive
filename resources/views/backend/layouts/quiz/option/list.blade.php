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
                                        <span class="info-box-text fw-bold" style="font-size: 2rem;">Options</span>
                                        <span class="info-box-number" style="font-size: .7rem;">
                        Manage all the options for your quiz questions here
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
                                    <h1 class="m-0">Options</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <a href="{{ route('options.create') }}" class="btn bg-gradient-teal btn-sm">
                                            <i class="fa fa-plus text-light"></i> Add New Option
                                        </a>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-primary text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Option Text</th>
                                    <th>Is Correct</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($options as $option)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $option->question->question_text ?? 'N/A' }}</td>
                                        <td>{{ $option->option_text }}</td>
                                        <td>{{ $option->is_correct ? 'Yes' : 'No' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('options.edit', $option->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{ route('options.destroy', $option->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this option?');">
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
                                        <td colspan="5" class="text-center">No options found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            {{-- Pagination --}}
                            <div class="mt-3">
                                {{ $options->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
