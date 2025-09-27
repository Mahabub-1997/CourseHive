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

        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">All About Us </h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('web-about-us.create') }}" class="btn bg-gradient-teal btn-sm">
                                <i class="fa fa-plus text-light"></i> Add New About Us
                            </a>
                        </ol>
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
                            <th>Title</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($aboutUsRecords as $aboutUs)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $aboutUs->title }}</td>
                                <td>{{ Str::limit($aboutUs->description, 50) }}</td>
                                <td>
                                    @if($aboutUs->image)
                                        <img src="{{ asset('storage/'.$aboutUs->image) }}" alt="{{ $aboutUs->title }}" width="80" height="50">
                                    @else
                                        N/A
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('web-about-us.edit', $aboutUs->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('web-about-us.destroy', $aboutUs->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this record?');">
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
                                <td colspan="5" class="text-center">No About Us records found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    {{ $aboutUsRecords->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection

