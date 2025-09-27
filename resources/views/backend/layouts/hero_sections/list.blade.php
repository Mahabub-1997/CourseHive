@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12 align-items-center">
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        {{-- Page Header --}}
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Hero Sections</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('web-hero-sections.create') }}" class="btn bg-gradient-teal btn-sm">
                            <i class="fa fa-plus text-light"></i> Add Hero Section
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Message --}}
        <div class="container-fluid">
            @if(Session::get('success'))
                <div class="alert alert-success alert-dismissible col-md-5">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <h5><i class="icon fas fa-check"></i> {{ Session::get('success') }}</h5>
                </div>
            @endif
        </div>

        {{-- Table Section --}}
        <div class="container-fluid">
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-gradient-teal text-white">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($heroSections as $hero)
                            <tr>
                                <td>{{ $hero->id }}</td>
                                <td>{{ $hero->title }}</td>
                                <td>{{ $hero->description }}</td>
                                <td>
                                    @if($hero->image)
                                        <img src="{{ asset('storage/' . $hero->image) }}" width="100" alt="Hero Section Image">
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('web-hero-sections.edit', $hero->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('web-hero-sections.destroy', $hero->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this hero section?');">
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
                                <td colspan="5" class="text-center">No hero sections found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $heroSections->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
