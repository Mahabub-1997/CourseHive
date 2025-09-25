@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-7 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Edit Hero Section') }}</div>
                <div class="card-body">

                    {{-- Show Validation Errors --}}
                    @if ($errors->any())
                        <div style="color: red;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Success Message --}}
                    @if(Session::get('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <h5><i class="icon fas fa-check"></i> {{ Session::get('success') }}</h5>
                        </div>
                    @endif

                    {{-- Form --}}
                    <form action="{{ route('web-hero-sections.update', $heroSection->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Title <i class="text-danger">*</i></label>
                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control" value="{{ old('title', $heroSection->title) }}" placeholder="Enter hero section title" required>
                            </div>
                        </div>

                        {{-- Description --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Description</label>
                            <div class="col-md-9">
                                <textarea name="description" rows="4" class="form-control" placeholder="Enter description">{{ old('description', $heroSection->description) }}</textarea>
                            </div>
                        </div>

                        {{-- Current Image --}}
                        @if($heroSection->image)
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Current Image</label>
                                <div class="col-md-9">
                                    <img src="{{ asset('storage/' . $heroSection->image) }}" alt="Hero Section Image" width="150">
                                </div>
                            </div>
                        @endif

                        {{-- Image --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Change Image</label>
                            <div class="col-md-9">
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="form-text text-muted">Upload a new image to replace the current one (jpg, png, max 2MB)</small>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Update Hero Section
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
