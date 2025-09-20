@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid mb-4">
            <div class="row" style="margin-top: 20px;">
                <div class="col-12">
                    {{-- Header Info Box --}}
                    <div class="container-fluid mb-4">
                        <div class="row" style="margin-top: 80px;">
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

                    {{-- Page Title + Add Button --}}
                    <div class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1 class="m-0">Options</h1>
                                </div>
                                <div class="col-sm-6 text-end">
                                    <a href="{{ route('options.create') }}" class="btn bg-gradient-teal btn-sm">
                                        <i class="fa fa-plus text-light"></i> Add New Option
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="bg-primary text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Question</th>
                                    <th>Options (4)</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($questions as $index => $question)
                                    <tr>
                                        {{-- Serial Number --}}
                                        <td>{{ $questions->firstItem() + $index }}</td>

                                        {{-- Question --}}
                                        <td>{{ $question->question_text }}</td>

                                        {{-- Options --}}
                                        <td>
                                            <ul class="list-unstyled">
                                                @foreach($question->options as $option)
                                                    <li>
                                                        {{-- If JSON (multiple languages) --}}
                                                        @if(is_array($option->option_text))
                                                            @foreach($option->option_text as $text)
                                                                {{ $text }}
                                                            @endforeach
                                                        @else
                                                            {{ $option->option_text }}
                                                        @endif

                                                        {{-- Highlight correct one --}}
                                                        @if($option->is_correct)
                                                            <span class="badge bg-success">✔</span>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-center">
                                            <a href="{{ route('options.edit', $question->id) }}"
                                               class="btn btn-info btn-sm"
                                               title="Edit Question & Options">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            {{-- Delete --}}
                                            <form action="{{ route('options.destroy', $question->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to delete this question and its options?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Question & Options">
                                                    <i class="fa fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">
                                            <i class="fa fa-info-circle"></i> No questions with options found.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>

                            {{-- Pagination --}}
                            <div class="mt-3">
                                {{ $questions->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


