@extends('layouts.admin_layout')

@section('title', 'Manage Car Media')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div id="error-container"></div>
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4">Manage Media for Car ID: {{ $item->id }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Manage Media</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div id="success-alert" class="alert alert-success alert-dismissible fade show" style="display: none;" role="alert">
                    <span id="success-message"></span>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Existing Media -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">Existing Media</h3>
                    </div>
                    <div class="card-body">
                        <div id="media-container" class="row">
                            @foreach($item->images as $media)
                                <div class="col-md-4 mb-4" style="max-height: 300px; max-width:300px" >
                                    <div class="card h-100">
                                        <div class="card-img-top" style="max-height: 60%">
                                            @if($media->type === 'image')
                                                <img src="{{ asset('storage/' . $media->file_path) }}" class="img-fluid" style="overflow: hidden" alt="{{ $media->alt }}">
                                            @elseif($media->type === 'video')
                                                <div class="embed-responsive embed-responsive-16by9">
                                                    <iframe src="https://www.youtube.com/embed/{{ $media->file_path }}" frameborder="0" allowfullscreen></iframe>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <p class="card-text">{{ $media->alt }}</p>
                                            <a href="{{ route('admin.'.$modelName.'.delete_image', $media->id) }}" class="btn btn-danger btn-block delete-media">Delete</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Upload Media -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title">Upload Images/Videos</h3>
                    </div>
                    <div class="card-body">
                        <form id="uploadMediaForm" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file_path">Upload Images/Videos</label>
                                <input type="file" name="file_path[]" class="form-control-file" id="mediaUpload" accept="image/*,video/*" multiple>
                            </div>

                            <div class="form-group">
                                <label>Preview:</label>
                                <div id="mediaPreviews" class="d-flex flex-wrap"></div>
                            </div>

                            <div class="form-group">
                                <label for="alt">Alt Text (Optional)</label>
                                <input type="text" name="alt" class="form-control" placeholder="Enter alt text">
                            </div>

                            <button type="submit" class="btn btn-success">Upload</button>
                        </form>
                    </div>
                </div>

                <!-- Add YouTube Videos -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h3 class="card-title">Add YouTube Videos</h3>
                    </div>
                    <div class="card-body">
                        <form id="youtubeForm">
                            @csrf
                            <div id="youtubeUrlsContainer">
                                <div class="form-group youtube-url-group">
                                    <label for="youtube_url">YouTube URL</label>
                                    <div class="input-group">
                                        <input type="text" name="youtube_urls[]" class="form-control youtubeUrl" placeholder="Enter YouTube video URL">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-outline-secondary preview-btn">Preview</button>
                                        </div>
                                    </div>
                                    <div class="youtube-preview mt-2" style="display: none;"></div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary" id="addYouTubeUrl">Add Another YouTube URL</button>
                            <button type="submit" class="btn btn-info">Add YouTube Videos</button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mediaUpload = document.getElementById('mediaUpload');
            const mediaPreviews = document.getElementById('mediaPreviews');
            const addYouTubeUrlBtn = document.getElementById('addYouTubeUrl');
            const youtubeUrlsContainer = document.getElementById('youtubeUrlsContainer');
            const uploadMediaForm = document.getElementById('uploadMediaForm');
            const youtubeForm = document.getElementById('youtubeForm');
            const mediaContainer = document.getElementById('media-container');
            const successAlert = document.getElementById('success-alert');
            const successMessage = document.getElementById('success-message');
            const errorContainer = document.getElementById('error-container');

            function showSuccessMessage(message) {
                successMessage.textContent = message;
                successAlert.style.display = 'block';
                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 5000);
            }

            function showErrorMessage(message) {
                errorContainer.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                `;
            }

            // Preview image or video before upload
            mediaUpload.addEventListener('change', function(event) {
                mediaPreviews.innerHTML = '';
                Array.from(event.target.files).forEach(file => {
                    const reader = new FileReader();
                    const previewContainer = document.createElement('div');
                    previewContainer.className = 'm-2';

                    reader.onload = function(e) {
                        if (file.type.startsWith('image/')) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-thumbnail';
                            img.style.width = '200px';
                            img.style.height = '200px';
                            previewContainer.appendChild(img);
                        } else if (file.type.startsWith('video/')) {
                            const video = document.createElement('video');
                            video.src = e.target.result;
                            video.controls = true;
                            video.className = 'img-thumbnail';
                            video.style.width = '200px';
                            video.style.height = '200px';
                            previewContainer.appendChild(video);
                        }
                    };
                    reader.readAsDataURL(file);
                    mediaPreviews.appendChild(previewContainer);
                });
            });

            // Add another YouTube URL input
            addYouTubeUrlBtn.addEventListener('click', function() {
                const newUrlGroup = document.createElement('div');
                newUrlGroup.className = 'form-group youtube-url-group';
                newUrlGroup.innerHTML = `
                    <label for="youtube_url">YouTube URL</label>
                    <div class="input-group">
                        <input type="text" name="youtube_urls[]" class="form-control youtubeUrl" placeholder="Enter YouTube video URL">
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary preview-btn">Preview</button>
                        </div>
                    </div>
                    <div class="youtube-preview mt-2" style="display: none;"></div>
                `;
                youtubeUrlsContainer.appendChild(newUrlGroup);
            });

            // YouTube preview functionality
            youtubeUrlsContainer.addEventListener('click', function(event) {
                if (event.target.classList.contains('preview-btn')) {
                    const urlInput = event.target.closest('.youtube-url-group').querySelector('.youtubeUrl');
                    const previewContainer = event.target.closest('.youtube-url-group').querySelector('.youtube-preview');
                    updateYouTubePreview(urlInput, previewContainer);
                }
            });

            function updateYouTubePreview(input, previewContainer) {
                const youtubeUrl = input.value;
                if (youtubeUrl) {
                    const videoId = extractYoutubeVideoId(youtubeUrl);
                    if (videoId) {
                        const embedUrl = `https://www.youtube.com/embed/${videoId}`;
                        previewContainer.innerHTML = `
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" src="${embedUrl}" allowfullscreen></iframe>
                            </div>
                        `;
                        previewContainer.style.display = 'block';
                    } else {
                        previewContainer.innerHTML = '<p class="text-danger">Invalid YouTube URL</p>';
                        previewContainer.style.display = 'block';
                    }
                } else {
                    previewContainer.style.display = 'none';
                }
            }

            function extractYoutubeVideoId(url) {
                const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
                const match = url.match(regExp);
                return (match && match[2].length === 11) ? match[2] : null;
            }

            // Upload media (images/videos) using AJAX
            uploadMediaForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('car_id', '{{ $item->id }}'); // Assuming 'car_id' for the item

                axios.post('{{ route("admin." . $modelName . ".storeImages") }}', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(response => {
                        showSuccessMessage('Media uploaded successfully');
                        mediaContainer.innerHTML += response.data.html;
                        uploadMediaForm.reset();
                        mediaPreviews.innerHTML = '';
                        location.reload();
                    })
                    .catch(error => {
                        showErrorMessage('Error uploading media: ' + error.response.data.message);
                    });
            });

            // Add YouTube videos using AJAX
            youtubeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('car_id', '{{ $item->id }}');

                axios.post('{{ route("admin." . $modelName . ".storeYouTube") }}', formData)
                    .then(response => {
                        showSuccessMessage('YouTube videos added successfully');
                        mediaContainer.innerHTML += response.data.html;
                        youtubeForm.reset();
                        document.querySelectorAll('.youtube-preview').forEach(preview => {
                            preview.style.display = 'none';
                        });
                        location.reload();
                    })
                    .catch(error => {
                        showErrorMessage('Error adding YouTube videos: ' + error.response.data.message);
                    });
            });

            // Delete media using AJAX
            mediaContainer.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-media')) {
                    e.preventDefault();
                    const deleteUrl = e.target.getAttribute('href');
                    const mediaItem = e.target.closest('.col-md-4');

                    if (confirm('Are you sure you want to delete this media?')) {
                        axios.delete(deleteUrl)
                            .then(response => {
                                showSuccessMessage('Media deleted successfully');
                                mediaItem.remove();
                            })
                            .catch(error => {
                                showErrorMessage('Error deleting media: ' + error.response.data.message);
                            });
                    }
                }
            });
        });
    </script>
@endpush

