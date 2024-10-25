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
                                        <label for="general_field" class="font-weight-bold">General Field</label>
                                        <input type="text" name="general_field" class="form-control form-control-lg shadow-sm" id="general_field" value="{{ old('general_field') }}">
                                    </div>

                                    <!-- Instagram Video Link Input -->
                                    <div class="form-group">
                                        <label for="instagram_link" class="font-weight-bold">Instagram Video Link</label>
                                        <input type="url" name="instagram_link" class="form-control form-control-lg shadow-sm" id="instagram_link" placeholder="Paste Instagram Video Link" value="{{ old('instagram_link') }}">
                                    </div>

                                    <!-- Video Preview -->
                                    <div class="form-group text-center">
                                        <label class="font-weight-bold">Video Preview</label>
                                        <div id="embedPreview" class="shadow-sm mb-3" style="max-width: 560px; height: auto;">
                                            <!-- The embedded Instagram video will be displayed here -->
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="is_active" class="font-weight-bold">Active</label>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active') }}>
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
        document.getElementById('instagram_link').addEventListener('input', function() {
            const instagramUrl = this.value;
            const embedPreview = document.getElementById('embedPreview');

            if (instagramUrl.includes('instagram.com')) {
                // Clear the preview
                embedPreview.innerHTML = 'Loading...';

                // Send a request to the backend to fetch the Instagram oEmbed data
                fetch('{{ route('admin.getInstagramEmbed') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ url: instagramUrl })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.html) {
                            embedPreview.innerHTML = data.html;

                            // Reparse the newly added HTML to apply Instagram's script
                            if (window.instgrm) {
                                window.instgrm.Embeds.process();
                            }
                        } else {
                            embedPreview.innerHTML = 'Failed to load the preview.';
                        }
                    })
                    .catch(error => {
                        embedPreview.innerHTML = 'Failed to load the preview.';
                        console.error('Error:', error);
                    });
            } else {
                embedPreview.innerHTML = '';
            }
        });
    </script>
@endpush
