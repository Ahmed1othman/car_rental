@extends('layouts.admin_layout')

@section('title', 'Add ' . $modelName)

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4">Add {{$modelName}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.' . $modelName . '.index') }}" style="text-transform: capitalize;">{{ $modelName }} List</a></li>
                            <li class="breadcrumb-item active" style="text-transform: capitalize;">Add {{$modelName}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px; color: #f44336;"></i>
                            <div class="flex-grow-1">
                                <strong>Oops! We found some issues:</strong>
                                <ol class="mt-2 mb-0 pl-4" style="list-style: decimal;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ol>
                            </div>
                            <button type="button" class="close ml-3" data-dismiss="alert" aria-label="Close" style="font-size: 20px;">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="card card-primary card-outline card-tabs shadow-lg">
                    <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> General Data
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.'.$modelName.'.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">

                                    <div class="form-group">
                                        <label for="instagram_url" class="font-weight-bold">Instagram Video URL</label>
                                        <input type="url" name="instagram_url" id="instagram_url" class="form-control" value="{{ old('instagram_url') }}" placeholder="Enter Instagram video URL" required>
                                    </div>

                                    <!-- Preview Section -->
                                    <div id="instagramPreview" class="mt-3" style="display: none;">
                                        <label class="font-weight-bold">Instagram Video Preview</label>
                                        <video id="instagramVideo" controls autoplay muted loop style="width: 100%; height: auto; border: none;"></video>
                                    </div>

                                    <div class="form-group">
                                        <label for="is_active" class="font-weight-bold">Active</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_active">Active</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success btn-lg mt-3">
                                <i class="fas fa-save"></i> Save
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('instagram_url').addEventListener('input', function() {
            const url = this.value;
            const videoElement = document.getElementById('instagramVideo');
            const previewSection = document.getElementById('instagramPreview');

            if (url) {
                // Display the preview section
                previewSection.style.display = 'block';

                // Set the video source
                videoElement.src = url;
            } else {
                // Hide the preview section if no URL
                previewSection.style.display = 'none';
            }
        });
    </script>
@endpush
