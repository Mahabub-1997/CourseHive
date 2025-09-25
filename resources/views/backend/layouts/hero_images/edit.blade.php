@extends('backend.partials.master')

@section('content')
    <div class="content-wrapper">
        <div class="col-md-8 py-5 mx-auto">
            <div class="card card-primary card-outline">
                <div class="card-header text-center">{{ __('Edit Hero Images') }}</div>
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

                    {{-- Form --}}
                    <form action="{{ route('web-hero-images.update', $heroImage->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Optional Title --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Title</label>
                            <div class="col-md-9">
                                <input type="text" name="title" class="form-control" value="{{ old('title', $heroImage->title) }}" placeholder="Enter title (optional)">
                            </div>
                        </div>

                        {{-- Existing Images --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Existing Images</label>
                            <div class="col-md-9">
                                <div class="d-flex flex-wrap gap-3">
                                    @foreach($heroImage->images as $index => $image)
                                        <div style="position: relative;">
                                            <img src="{{ asset('storage/' . $image) }}" width="100" style="border:1px solid #ccc; padding:2px;">
                                            <p class="mt-1">Replace:</p>
                                            <input type="file" name="images[{{ $index }}]" accept="image/*">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Add New Images --}}
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Add More Images</label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="new_images[]" accept="image/*" multiple>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="form-group row">
                            <div class="col-md-9 offset-md-3">
                                <input type="submit" class="btn btn-primary" value="Update Hero Images">
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
