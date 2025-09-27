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
                        <h1 class="m-0">All Contacts</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a href="{{ route('web-contactus.create') }}" class="btn bg-gradient-teal btn-sm">
                                <i class="fa fa-plus text-light"></i> Add New Contact
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
                            <th>Name</th>
                            <th>Email</th>
                            <th>Description</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($contacts as $contact)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $contact->name }}</td>
                                <td>{{ $contact->email }}</td>
                                <td>{{ Str::limit($contact->description, 50) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('web-contactus.edit', $contact->id) }}" class="btn btn-info btn-sm">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('web-contactus.destroy', $contact->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this record?');">
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
                                <td colspan="5" class="text-center">No Contact Us records found</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- Pagination --}}
                    {{ $contacts->links('pagination::bootstrap-5') }}
                </div>

            </div>
        </div>
    </div>
@endsection
