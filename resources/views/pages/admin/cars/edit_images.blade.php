@extends('layouts.admin_layout')

@section('title', 'Manage Car Media')

@push('styles')
<style>
    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin: 20px 0;
    }

    .preview-item {
        position: relative;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        height: 200px;
        width: 293px;
        margin: 10px 5px 10px 5px;
        display: flex
    ;
        flex-direction: column;
        gap: 10px;
    }

    .preview-media {
        flex: 1;
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 4px;
    }

    .preview-delete {
        position: absolute;
        top: 5px;
        right: 5px;
        background: rgba(255, 0, 0, 0.8);
        color: white;
        border-radius: 50%;
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
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

    .media-card {
        height: 250px;
        margin-bottom: 15px;
    }

    .media-card .card-body {
        padding: 10px;
    }

    .media-preview {
        height: 180px;
        object-fit: cover;
        width: 100%;
        border-radius: 4px;
    }

    .media-actions {
        padding: 5px 0;
    }

    .media-alt {
        font-size: 12px;
        color: #666;
        margin-bottom: 5px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
        padding: 15px;
    }
</style>
@endpush

@section('content')
<div class="content-wrapper">
    <!-- Loader Overlay -->
    <div class="loader-overlay" id="loader-overlay">
        <div class="text-center">
            <div class="loader"></div>
            <div class="mt-2">Processing Media...</div>
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
                    <h3 class="card-title">Current Media</h3>
                </div>
                <div class="card-body p-2">
                    <div class="row" id="media-container">
                        @foreach($item->images as $media)
                            <div class="col-md-3 col-sm-6">
                                <div class="card media-card">
                                    <div class="card-body p-2">
                                        @if($media->type == 'video')
                                            <video class="media-preview" controls>
                                                <source src="{{ asset('storage/' . $media->file_path) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <img src="{{ asset('storage/' . $media->file_path) }}" 
                                                 class="media-preview"
                                                 alt="{{ $media->alt }}">
                                        @endif
                                        <div class="media-actions">
                                            @if($media->alt)
                                                <div class="media-alt">{{ $media->alt }}</div>
                                            @endif
                                            <button class="btn btn-danger btn-sm btn-block delete-media" 
                                                    data-id="{{ $media->id }}">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Default Image Upload -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h3 class="card-title">Default Image</h3>
                </div>
                <div class="card-body">
                    <form id="defaultImageForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="defaultImage">Select Default Image</label>
                            <input type="file" class="form-control-file" id="defaultImage" 
                                   name="image" accept="image/*" required>
                            <div id="defaultImagePreview" class="mt-3">
                                @if($item->default_image_path)
                                    <img src="{{ asset('storage/' . $item->default_image_path) }}" 
                                         alt="Default Image" 
                                         class="media-preview" 
                                         style="max-height: 200px; width: auto;">
                                @endif
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Upload Default Image</button>
                    </form>
                </div>
            </div>

            <!-- Additional Media Upload -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title">Upload Images & Videos</h3>
                </div>
                <div class="card-body">
                    <form id="mediaUploadForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="mediaUpload">Select Files</label>
                            <input type="file" class="form-control-file" id="mediaUpload" 
                                   name="files[]" multiple accept="image/*,video/*" required>
                            <small class="form-text text-muted">
                                Supported formats: Images (JPG, PNG, GIF) and Videos (MP4, WebM)
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="alt">Alt Text (Optional)</label>
                            <input type="text" class="form-control" id="alt" name="alt" 
                                   placeholder="Enter alt text for the media">
                        </div>
                        <div class="row" id="mediaPreviewContainer">
                            <!-- Preview will be shown here -->
                        </div>
                        <button type="submit" class="btn btn-info">Upload Media</button>
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
        const mediaPreviewContainer = document.getElementById('mediaPreviewContainer');
        const mediaUploadForm = document.getElementById('mediaUploadForm');
        const loaderOverlay = document.getElementById('loader-overlay');
        const progressBar = document.querySelector('.progress-bar');
        const successAlert = document.getElementById('success-alert');
        const successMessage = document.getElementById('success-message');
        let selectedFiles = new Map();

        function createPreviewElement(file, fileId) {
            const previewItem = document.createElement('div');
            previewItem.className = 'preview-item';
            previewItem.dataset.fileId = fileId;

            // Add delete button
            const deleteBtn = document.createElement('div');
            deleteBtn.className = 'preview-delete';
            deleteBtn.innerHTML = '×';
            deleteBtn.onclick = function() {
                selectedFiles.delete(fileId);
                previewItem.remove();
                updateFileInput();
            };

            // Create preview
            if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.className = 'preview-media';
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
                previewItem.appendChild(img);
            } else if (file.type.startsWith('video/')) {
                const video = document.createElement('video');
                video.className = 'preview-media';
                video.controls = true;
                const reader = new FileReader();
                reader.onload = function(e) {
                    video.src = e.target.result;
                };
                reader.readAsDataURL(file);
                previewItem.appendChild(video);
            }

            // Add file name
            const fileName = document.createElement('div');
            fileName.className = 'media-alt';
            fileName.textContent = file.name;
            
            previewItem.appendChild(deleteBtn);
            previewItem.appendChild(fileName);
            return previewItem;
        }

        function updateFileInput() {
            const dataTransfer = new DataTransfer();
            selectedFiles.forEach(file => {
                dataTransfer.items.add(file);
            });
            mediaUpload.files = dataTransfer.files;
        }

        mediaUpload.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            mediaPreviewContainer.innerHTML = '';
            selectedFiles.clear();
            
            files.forEach(file => {
                const fileId = Date.now() + Math.random().toString(36).substr(2, 9);
                selectedFiles.set(fileId, file);
                const previewElement = createPreviewElement(file, fileId);
                mediaPreviewContainer.appendChild(previewElement);
            });
            
            updateFileInput();
        });

        mediaUploadForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            if (selectedFiles.size === 0) {
                showMessage('Please select files to upload', true);
                return;
            }

            showLoader();
            const formData = new FormData();
            
            selectedFiles.forEach((file) => {
                formData.append('files[]', file);
            });
            formData.append('alt', document.getElementById('alt').value);

            try {
                const response = await axios.post(`/cars/{{ $item->id }}/upload-image`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                    onUploadProgress: (progressEvent) => {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        if (progressBar) {
                            progressBar.style.width = percentCompleted + '%';
                        }
                    }
                });

                if (response.data.success) {
                    showMessage(response.data.message);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showMessage(response.data.message, true);
                }
            } catch (error) {
                showMessage(error.response?.data?.message || 'Error uploading media', true);
            } finally {
                hideLoader();
            }
        });

        function showLoader() {
            if (loaderOverlay) {
                loaderOverlay.style.display = 'flex';
            }
            if (progressBar) {
                progressBar.style.width = '0%';
                progressBar.parentElement.style.display = 'block';
            }
        }

        function hideLoader() {
            if (loaderOverlay) {
                loaderOverlay.style.display = 'none';
            }
            if (progressBar) {
                progressBar.parentElement.style.display = 'none';
            }
        }

        function showMessage(message, isError = false) {
            if (successAlert && successMessage) {
                successMessage.textContent = message;
                successAlert.classList.remove('alert-success', 'alert-danger');
                successAlert.classList.add(isError ? 'alert-danger' : 'alert-success');
                successAlert.style.display = 'block';
                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 5000);
            }
        }

        // Delete media functionality
        document.querySelectorAll('.delete-media').forEach(button => {
            button.addEventListener('click', function() {
                const mediaId = this.getAttribute('data-id');
                const mediaCard = this.closest('.col-md-3');
                
                if (confirm('Are you sure you want to delete this media?')) {
                    // Show loader while deleting
                    showLoader();
                    
                    axios.delete(`/admin/cars/delete-image/${mediaId}`)
                        .then(response => {
                            if (response.data.success) {
                                // Remove the media card from the DOM
                                mediaCard.remove();
                                showMessage(response.data.message);
                            } else {
                                alert(response.data.message || 'Failed to delete media');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert(error.response?.data?.message || 'Failed to delete media. Please try again.');
                        })
                        .finally(() => {
                            // Hide loader
                            hideLoader();
                        });
                }
            });
        });

        // Preview for default image
        const defaultImage = document.getElementById('defaultImage');
        const defaultImagePreview = document.getElementById('defaultImagePreview');
        defaultImage.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    defaultImagePreview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="max-height: 200px;">
                    `;
                }
                reader.readAsDataURL(file);
            }
        });

        // Handle default image upload
        const defaultImageForm = document.getElementById('defaultImageForm');
        defaultImageForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            showLoader();

            const formData = new FormData(this);
            try {
                const response = await axios.post(`/cars/{{ $item->id }}/upload-default-image`, formData, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                    onUploadProgress: (progressEvent) => {
                        const percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        if (progressBar) {
                            progressBar.style.width = percentCompleted + '%';
                        }
                    }
                });

                if (response.data.success) {
                    showMessage(response.data.message);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showMessage(response.data.message, true);
                }
            } catch (error) {
                showMessage(error.response?.data?.message || 'Error uploading default image', true);
            } finally {
                hideLoader();
            }
        });
    });
</script>
@endpush
