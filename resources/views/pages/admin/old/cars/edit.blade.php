@extends('layouts.admin_layout')

@section('title', ' تعديل '.$modelName )

@section('content')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0"> تعديل {{$modelName}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.'.$modelName.'.index') }}">{{$modelName}}</a></li>
                            <li class="breadcrumb-item active"> تعديل {{$modelName}}</li>
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
                                <h3 class="card-title"> تعديل {{$modelName}}</h3>
                            </div>
                            <!-- form start -->
                            <form action="{{ route('admin.'.$modelName.'.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <!-- Brand Information -->
                                    <div class="form-group">
                                        <h4> معلومات {{$modelName}}</h4>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name_en"> اسم {{$modelName}} (بالإنجليزية) <span class="text-danger">*</span></label>
                                            <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" id="name_en" value="{{ old('name_en', $item->name_en) }}" placeholder="أدخل اسم {{$modelName}} بالإنجليزية" required>
                                            @error('name_en')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="name_ar"> اسم {{$modelName}} (بالعربية) <span class="text-danger">*</span></label>
                                            <input type="text" name="name_ar" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" value="{{ old('name_ar', $item->name_ar) }}" placeholder="أدخل اسم {{$modelName}} بالعربية" required>
                                            @error('name_ar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Logo Upload -->
                                    <div class="form-group mt-4">
                                        <h4>تحميل الشعار</h4>
                                    </div>

                                    <div class="form-group">
                                        <div class="mb-3">
                                            <img id="imagePreview" src="{{ asset('storage/' . $item->logo_path) }}" alt="Image Preview" class="brand-logo img-thumbnail" style="max-height: 100px;">
                                        </div>
                                        <div class="custom-file">
                                            <input type="file" name="logo_path" class="custom-file-input @error('logo_path') is-invalid @enderror" id="logo_path" onchange="previewImage(event)">
                                            <label class="custom-file-label" for="logo_path">اختر صورة</label>
                                        </div>
                                        @error('logo_path')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Styled Horizontal Rule -->
                                    <hr style="border: 0; height: 1px; background: linear-gradient(to right, #007bff, #00d8ff); margin: 30px 0;">

                                    <!-- Meta Information -->
                                    <div class="form-group">
                                        <h4>معلومات السيو</h4>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="meta_title_en">العنوان التعريفي (بالإنجليزية)</label>
                                            <input type="text" name="meta_title_en" class="form-control @error('meta_title_en') is-invalid @enderror" id="meta_title_en" value="{{ old('meta_title_en', $item->meta_title_en) }}" placeholder="أدخل العنوان التعريفي بالإنجليزية">
                                            @error('meta_title_en')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="meta_title_ar">العنوان التعريفي (بالعربية)</label>
                                            <input type="text" name="meta_title_ar" class="form-control @error('meta_title_ar') is-invalid @enderror" id="meta_title_ar" value="{{ old('meta_title_ar', $item->meta_title_ar) }}" placeholder="أدخل العنوان التعريفي بالعربية">
                                            @error('meta_title_ar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="meta_description_en">الوصف التعريفي (بالإنجليزية)</label>
                                            <textarea name="meta_description_en" class="form-control @error('meta_description_en') is-invalid @enderror" id="meta_description_en" placeholder="أدخل الوصف التعريفي بالإنجليزية">{{ old('meta_description_en', $item->meta_description_en) }}</textarea>
                                            @error('meta_description_en')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="meta_description_ar">الوصف التعريفي (بالعربية)</label>
                                            <textarea name="meta_description_ar" class="form-control @error('meta_description_ar') is-invalid @enderror" id="meta_description_ar" placeholder="أدخل الوصف التعريفي بالعربية">{{ old('meta_description_ar', $item->meta_description_ar) }}</textarea>
                                            @error('meta_description_ar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="meta_keywords_en">الكلمات المفتاحية (بالإنجليزية)</label>
                                            <input type="text" name="meta_keywords_en" class="form-control @error('meta_keywords_en') is-invalid @enderror" id="meta_keywords_en" value="{{ old('meta_keywords_en', $item->meta_keywords_en) }}" placeholder="أدخل الكلمات المفتاحية بالإنجليزية">
                                            @error('meta_keywords_en')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="meta_keywords_ar">الكلمات المفتاحية (بالعربية)</label>
                                            <input type="text" name="meta_keywords_ar" class="form-control @error('meta_keywords_ar') is-invalid @enderror" id="meta_keywords_ar" value="{{ old('meta_keywords_ar', $item->meta_keywords_ar) }}" placeholder="أدخل الكلمات المفتاحية بالعربية">
                                            @error('meta_keywords_ar')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                                <!-- /.card-body -->

                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ التعديلات</button>
                                    <div id="loading_spinner" style="display: none;">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">جاري التحميل...</span>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
            </div>
        </section>
    </div>

    @push('scripts')
        <!-- Include Tagify library -->
        <script>
            function previewImage(event) {
                const imagePreview = document.getElementById('imagePreview');
                const file = event.target.files[0];
                const reader = new FileReader();

                reader.onload = function() {
                    imagePreview.src = reader.result;
                    imagePreview.style.display = 'block';
                }

                if (file) {
                    reader.readAsDataURL(file);
                } else {
                    imagePreview.style.display = 'none';
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Tagify on keywords inputs
                new Tagify(document.getElementById('meta_keywords_en'), {
                    placeholder: 'أدخل الكلمات المفتاحية بالإنجليزية',
                });

                new Tagify(document.getElementById('meta_keywords_ar'), {
                    placeholder: 'أدخل الكلمات المفتاحية بالعربية',
                });
            });
        </script>
    @endpush

@endsection
