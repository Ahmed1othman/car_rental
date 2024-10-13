@extends('layouts.admin_layout')

@section('title', 'إضافة ' . $modelName)

@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">إضافة {{$modelName}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">الرئيسية</a></li>
                            <li class="breadcrumb-item active">إضافة {{$modelName}}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">إضافة {{$modelName}}</h3>
                            </div>
                            <!-- form start -->
                            <form action="{{ route('admin.' . $modelName . '.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">

                                    <!-- Car Information -->
                                    <div class="form-group">
                                        <h4>معلومات {{$modelName}}</h4>
                                    </div>
                                    <!-- Styled Horizontal Rule -->
                                    <hr style="border: 0; height: 1px; background: linear-gradient(to right, #007bff, #00d8ff); margin: 30px 0;">
                                    <!-- Name Fields -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name_en">اسم السيارة (بالإنجليزية)</label>
                                            <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" id="name_en" value="{{ old('name_en') }}" placeholder="أدخل اسم السيارة بالإنجليزية">
                                            @error('name_en')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="name_ar">اسم السيارة (بالعربية)</label>
                                            <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" value="{{ old('name_ar') }}" placeholder="أدخل اسم السيارة بالعربية">
                                            @error('name_ar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Brand, Category, Car Type -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="brand_id">العلامة التجارية</label>
                                            <select name="brand_id" id="brand_id" class="form-control @error('brand_id') is-invalid @enderror">
                                                <option value="">اختر العلامة التجارية</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name_en }} ({{ $brand->name_ar }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('brand_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="category_id">الفئة</label>
                                            <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                                                <option value="">اختر الفئة</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name_en }} ({{ $category->name_ar }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>



                                    <!-- Lookup Fields -->
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="body_style_id">نمط الجسم</label>
                                            <select name="body_style_id" id="body_style_id" class="form-control @error('body_style_id') is-invalid @enderror">
                                                <option value="">اختر نمط الجسم</option>
                                                @foreach($bodyStyles as $bodyStyle)
                                                    <option value="{{ $bodyStyle->id }}" {{ old('body_style_id') == $bodyStyle->id ? 'selected' : '' }}>
                                                        {{ $bodyStyle->name_en }} ({{ $bodyStyle->name_ar }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('body_style_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="car_maker_id">صانع السيارة</label>
                                            <select name="car_maker_id" id="car_maker_id" class="form-control @error('car_maker_id') is-invalid @enderror">
                                                <option value="">اختر صانع السيارة</option>
                                                @foreach($carMakers as $carMaker)
                                                    <option value="{{ $carMaker->id }}" {{ old('car_maker_id') == $carMaker->id ? 'selected' : '' }}>
                                                        {{ $carMaker->name_en }} ({{ $carMaker->name_ar }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('car_maker_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="includes">المميزات الاضافية:</label>
                                        @foreach($includedFeatures as $include)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="includes[]" value="{{ $include->id }}" id="include_{{ $include->id }}" {{ in_array($include->id, old('includes', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="include_{{ $include->id }}">{{ $include->name_en }} ({{ $include->name_ar }})</label>
                                            </div>
                                        @endforeach
                                    </div>



                                    <hr style="margin-top: 10px;">
                                    <!-- SEO Fields -->
                                    <div class="form-group">
                                        <h4>بيانات SEO</h4>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="meta_title_en">Meta Title (بالإنجليزية)</label>
                                            <input type="text" name="meta_title_en" class="form-control @error('meta_title_en') is-invalid @enderror" id="meta_title_en" value="{{ old('meta_title_en') }}" placeholder="أدخل Meta Title بالإنجليزية">
                                            @error('meta_title_en')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="meta_title_ar">Meta Title (بالعربية)</label>
                                            <input type="text" name="meta_title_ar" class="form-control @error('meta_title_ar') is-invalid @enderror" id="meta_title_ar" value="{{ old('meta_title_ar') }}" placeholder="أدخل Meta Title بالعربية">
                                            @error('meta_title_ar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="meta_description_en">Meta Description (بالإنجليزية)</label>
                                            <textarea name="meta_description_en" class="form-control @error('meta_description_en') is-invalid @enderror" id="meta_description_en" rows="3" placeholder="أدخل Meta Description بالإنجليزية">{{ old('meta_description_en') }}</textarea>
                                            @error('meta_description_en')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="meta_description_ar">Meta Description (بالعربية)</label>
                                            <textarea name="meta_description_ar" class="form-control @error('meta_description_ar') is-invalid @enderror" id="meta_description_ar" rows="3" placeholder="أدخل Meta Description بالعربية">{{ old('meta_description_ar') }}</textarea>
                                            @error('meta_description_ar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="meta_keywords_en">Meta Keywords (بالإنجليزية)</label>
                                            <textarea name="meta_keywords_en" class="form-control @error('meta_keywords_en') is-invalid @enderror" id="meta_keywords_en" rows="3" placeholder="أدخل Meta Keywords بالإنجليزية">{{ old('meta_keywords_en') }}</textarea>
                                            @error('meta_keywords_en')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="meta_keywords_ar">Meta Keywords (بالعربية)</label>
                                            <textarea name="meta_keywords_ar" class="form-control @error('meta_keywords_ar') is-invalid @enderror" id="meta_keywords_ar" rows="3" placeholder="أدخل Meta Keywords بالعربية">{{ old('meta_keywords_ar') }}</textarea>
                                            @error('meta_keywords_ar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->


                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">حفظ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const imageUploadInput = document.getElementById('image-upload');
                const imagePreviewsContainer = document.getElementById('image-previews');
                const removedFilesInput = document.getElementById('removed-files');

                imageUploadInput.addEventListener('change', function () {
                    updatePreviews();
                });

                function updatePreviews() {
                    imagePreviewsContainer.innerHTML = ''; // Clear previous previews
                    const files = Array.from(imageUploadInput.files);

                    files.forEach((file, index) => {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            const previewDiv = document.createElement('div');
                            previewDiv.classList.add('col-md-3', 'mb-3');

                            previewDiv.innerHTML = `
                        <div class="card">
                            <img src="${e.target.result}" class="card-img-top" alt="Preview Image" style="height: 150px; object-fit: cover;">
                            <div class="card-body text-center">
                                <input type="radio" name="default_image" value="${index}" ${index === 0 ? 'checked' : ''}>
                                <label>تعيين كافتراضي</label>
                                <button type="button" class="btn btn-sm btn-danger mt-2 remove-image" data-index="${index}">إزالة</button>
                            </div>
                        </div>
                    `;
                            imagePreviewsContainer.appendChild(previewDiv);
                        };
                        reader.readAsDataURL(file);
                    });
                }

                function attachRemoveListeners() {
                    imagePreviewsContainer.addEventListener('click', function (e) {
                        if (e.target && e.target.matches('.remove-image')) {
                            removeImage(e.target);
                        }
                    });
                }

                function removeImage(button) {
                    const indexToRemove = parseInt(button.getAttribute('data-index'));
                    removeFile(indexToRemove);
                }

                function removeFile(indexToRemove) {
                    const files = Array.from(imageUploadInput.files);
                    const remainingFiles = files.filter((_, index) => index !== indexToRemove);

                    // Create a new DataTransfer object
                    const dataTransfer = new DataTransfer();
                    remainingFiles.forEach(file => dataTransfer.items.add(file));

                    imageUploadInput.files = dataTransfer.files;

                    // Update the removed files input
                    let removedFileIndexes = removedFilesInput.value ? removedFilesInput.value.split(',') : [];
                    if (!removedFileIndexes.includes(indexToRemove.toString())) {
                        removedFileIndexes.push(indexToRemove);
                        removedFileIndexes = Array.from(new Set(removedFileIndexes)); // Remove duplicates
                        removedFilesInput.value = removedFileIndexes.join(',');
                    }

                    updatePreviews();
                }

                // Initial call to set up the event listeners
                attachRemoveListeners();
            });
        </script>
    @endpush
@endsection










