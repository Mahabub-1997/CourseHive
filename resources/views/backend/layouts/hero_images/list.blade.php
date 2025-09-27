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
                        <h1 class="m-0">Hero Images</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        <a href="{{ route('web-hero-images.create') }}" class="btn bg-gradient-teal btn-sm">
                            <i class="fa fa-plus text-light"></i> Add Hero Images
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="card">
            <div class="card-body">
                @if(Session::get('success'))
                    <div class="alert alert-success alert-dismissible col-md-5">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <h5><i class="icon fas fa-check"></i> {{ Session::get('success') }}</h5>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="bg-gradient-teal text-white">
                        <tr>
                            <th>#</th>
                            <th>Images</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($heroImages as $heroImage)
                            <tr>
                                <td>{{ $heroImage->id }}</td>
                                <td>
                                    @foreach($heroImage->images as $img)
                                        <img src="{{ asset('storage/'.$img) }}" width="100" style="margin:3px;" alt="Hero Image">
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('web-hero-images.edit', $heroImage->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('web-hero-images.destroy', $heroImage->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this hero images group?');">
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
                                <td colspan="3" class="text-center">No hero images found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    <div class="mt-3">
                        {{ $heroImages->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
