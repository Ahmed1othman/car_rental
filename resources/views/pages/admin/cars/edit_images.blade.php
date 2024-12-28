@extends('layouts.admin_layout')

@section('title', 'Manage Car Media')

@push('styles')
<style>
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .preview-item {
        position: relative;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
    }

    .preview-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 4px;
    }

    .loader-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }

    .loader {
        width: 50px;
        height: 50px;
        border: 5px solid #f3f3f3;
        border-radius: 50%;
        border-top: 5px solid #3498db;
        animation: spin 1s linear infinite;
    }

    .upload-progress {
        width: 100%;
        background-color: #f3f3f3;
        padding: 3px;
        border-radius: 3px;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, .2);
        margin-top: 10px;
        display: none;
    }

    .progress-bar {
        display: block;
        height: 20px;
        background-color: #3498db;
        border-radius: 3px;
        transition: width 500ms ease-in-out;
        width: 0%;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Loader Overlay -->
    <div class="loader-overlay" id="loader-overlay">
        <div class="text-center">
            <div class="loader"></div>
            <div class="mt-2">Processing Images...</div>
            <div class="upload-progress">
                <div class="progress-bar" role="progressbar"></div>
            </div>
        </div>
    </div>

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
                                    <div class="card-img-top" style="height: 80%">
                                        <div style="height: 100%">
                                            @if($media->type === 'image')
                                            <img src="{{ asset('storage/' . $media->file_path) }}" class="img-fluid" style="overflow: hidden;height: 100%;width: 100%;object-fit: cover;" alt="{{ $media->alt }}">
                                        @elseif($media->type === 'video')
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe src="https://www.youtube.com/embed/{{ $media->file_path }}" frameborder="0" allowfullscreen></iframe>
                                            </div>
                                        @endif
                                        </div>
                                    
                                    </div>
                                    <div>
                                        <p class="card-text">{{ $media->alt }}</p>
                                        <a href="{{ route('admin.'.$modelName.'.delete_image', $media->id) }}" class="btn btn-danger btn-block delete-media">Delete</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <h3 class="card-title">Upload Default Image</h3>
                </div>
                <div class="card-body">
                    <form id="uploadDefaultImageForm" action="{{ route('cars.upload-default-image', $item->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="font-weight-bold">Default Image</label>
                            <div class="custom-file">
                                <input type="file" name="image" class="custom-file-input image-upload" id="default_image_path" data-preview="imagePreviewLogo" accept="image/*" required>
                                <label class="custom-file-label" for="default_image_path">Upload Default Image</label>
                            </div>
                            <div class="mt-3">
                                <img id="imagePreviewLogo" src="{{$item->default_image_path?asset('storage/'.$item->default_image_path):'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\' viewBox=\'0 0 400 400\'%3E%3Crect width=\'100%25\' height=\'100%25\' fill=\'%23ddd\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' fill=\'%23555\' font-size=\'20\' text-anchor=\'middle\' dy=\'.3em\'%3E400x400%3C/text%3E%3C/svg%3E' }}" alt="Logo Preview" class="shadow image-rectangle-preview" style="max-height: 300px; width: 300px; object-fit: cover; border: 2px solid #ddd;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger">Upload Default Image</button>
                    </form>
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
        const uploadDefaultImageForm = document.getElementById('uploadDefaultImageForm');
        const youtubeForm = document.getElementById('youtubeForm');
        const mediaContainer = document.getElementById('media-container');
        const successAlert = document.getElementById('success-alert');
        const successMessage = document.getElementById('success-message');
        const errorContainer = document.getElementById('error-container');
        const loaderOverlay = document.getElementById('loader-overlay');

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

        function showLoader() {
            loaderOverlay.style.display = 'flex';
        }

        function hideLoader() {
            loaderOverlay.style.display = 'none';
            document.querySelector('.upload-progress').style.display = 'none';
            document.querySelector('.progress-bar').style.width = '0%';
        }

        function updateProgress(percent) {
            const progressBar = document.querySelector('.progress-bar');
            const progressContainer = document.querySelector('.upload-progress');
            progressContainer.style.display = 'block';
            progressBar.style.width = percent + '%';
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
            showLoader();

            const formData = new FormData(this);
            formData.append('car_id', '{{ $item->id }}');

            const xhr = new XMLHttpRequest();

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    updateProgress(percentComplete);
                }
            };

            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                showSuccessMessage('Media uploaded successfully');
                                uploadMediaForm.reset();
                                mediaPreviews.innerHTML = '';
                                // Force reload after a brief delay
                                setTimeout(function() {
                                    window.location.href = window.location.href;
                                    window.location.reload();
                                }, 500);
                                window.location.reload();
                            } else {
                                showErrorMessage(response.message || 'Error uploading media');
                                hideLoader();
                                window.location.reload();
                            }
                        } catch (error) {
                            console.error('Error parsing response:', error);
                            showErrorMessage('Error processing server response');
                            hideLoader();
                            window.location.reload();
                        }
                    } else {
                        showErrorMessage('Error uploading media');
                        hideLoader();
                        window.location.reload();
                    }
                }
            };

            xhr.onerror = function() {
                showErrorMessage('Error uploading media');
                hideLoader();
            };

            xhr.open('POST', '{{ route("admin." . $modelName . ".storeImages") }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.send(formData);
        });

        uploadDefaultImageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showLoader();

            const formData = new FormData(this);

            const xhr = new XMLHttpRequest();

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    updateProgress(percentComplete);
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showSuccessMessage('Default image uploaded successfully');
                        const previewImg = document.getElementById('imagePreviewLogo');
                        if (response.data && response.data.image_url) {
                            previewImg.src = response.data.image_url + '?t=' + new Date().getTime();
                        }
                        location.reload();
                    } else {
                        showErrorMessage(response.message || 'Error uploading default image');
                    }
                } else {
                    showErrorMessage('Error uploading default image');
                }
                hideLoader();
            };

            xhr.onerror = function() {
                showErrorMessage('Error uploading default image');
                hideLoader();
            };

            xhr.open('POST', this.action, true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.send(formData);
        });

        // Add YouTube videos using AJAX
        youtubeForm.addEventListener('submit', function(e) {
            e.preventDefault();
            showLoader();

            const formData = new FormData(this);
            formData.append('car_id', '{{ $item->id }}');

            const xhr = new XMLHttpRequest();

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentComplete = (e.loaded / e.total) * 100;
                    updateProgress(percentComplete);
                }
            };

            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        showSuccessMessage('YouTube videos added successfully');
                        mediaContainer.innerHTML += response.data.html;
                        youtubeForm.reset();
                        document.querySelectorAll('.youtube-preview').forEach(preview => {
                            preview.style.display = 'none';
                        });
                        location.reload();
                    } else {
                        showErrorMessage('Error adding YouTube videos: ' + response.data.message);
                    }
                } else {
                    showErrorMessage('Error adding YouTube videos');
                }
                hideLoader();
            };

            xhr.onerror = function() {
                showErrorMessage('Error adding YouTube videos');
                hideLoader();
            };

            xhr.open('POST', '{{ route("admin." . $modelName . ".storeYouTube") }}', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.send(formData);
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
