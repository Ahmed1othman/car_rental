@extends('layouts.admin_layout')

@section('title', 'Add ' . $modelName)

@push('styles')
    <style>
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
        }

        .preview-item {
            position: relative;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            text-align: center;
            overflow: hidden;
        }

        .preview-item img,
        .preview-item iframe {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .remove-preview {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #ff4d4d;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            font-size: 14px;
            line-height: 30px;
            text-align: center;
            cursor: pointer;
        }

        .remove-preview:hover {
            background-color: #e60000;
        }

        .preview-item:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .loader-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loader {
            border: 5px solid #f3f3f3;
            border-radius: 50%;
            border-top: 5px solid #3498db;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush
@section('content')
    <!-- Loader Overlay -->
    <div class="loader-overlay" id="loader-overlay">
        <div class="loader"></div>
    </div>

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
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px;"></i>
                            <div class="flex-grow-1">
                                <h5 class="alert-heading mb-2">Please correct the following errors:</h5>
                                <ul class="mb-0 pl-3">
                                    @foreach($errors->getBag('default')->toArray() as $field => $errorMessages)
                                        @foreach($errorMessages as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            </div>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                <div class="card card-primary card-outline card-tabs shadow-lg">
                    <div class="card-header p-0 pt-1 border-bottom-0 bg-light">
                        <!-- Tabs Header -->
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <!-- General Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> General Data
                                </a>
                            </li>
                            <!-- Translated Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-translated-tab" data-toggle="pill" href="#custom-tabs-translated" role="tab" aria-controls="custom-tabs-translated" aria-selected="false">
                                    <i class="fas fa-language"></i> Translated Data
                                </a>
                            </li>
                            <!-- SEO Data Tab -->
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-seo-tab" data-toggle="pill" href="#custom-tabs-seo" role="tab" aria-controls="custom-tabs-seo" aria-selected="false">
                                    <i class="fas fa-search"></i> SEO Data
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <!-- Form -->
                        <form id="createCarForm" action="{{ route('admin.'.$modelName.'.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <!-- General Data Tab Content -->
                                <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">

                                    <!-- General Data Tab Content -->
                                    <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">
                                        <!-- Car Information -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Car Information</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="brand_id" class="font-weight-bold">Brand</label>
                                                            <select name="brand_id" id="brand_id" class="form-control shadow-sm select2 @error('brand_id') is-invalid @enderror">
                                                                <option value="">-- Select Brand --</option>
                                                                @foreach($brands as $brand)
                                                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                                        {{ $brand->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('brand_id')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="model_id" class="font-weight-bold">Model</label>
                                                            <select name="model_id" id="model_id" class="form-control shadow-sm select2">
                                                                <option value="">-- Select Model --</option>
                                                                <!-- Models will be populated dynamically here -->
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="category_id" class="font-weight-bold">Car Category</label>
                                                            <select name="category_id" id="category_id" class="form-control shadow-sm select2 @error('category_id') is-invalid @enderror">
                                                                <option value="">-- Select Category --</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('category_id')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gearType_id" class="font-weight-bold">Gear Type</label>
                                                            <select name="gear_type_id" id="gear_type_id" class="form-control shadow-sm select2 @error('gear_type_id') is-invalid @enderror">
                                                                <option value="">-- Select Gear Type --</option>
                                                                @foreach($gearTypes as $gearType)
                                                                    <option value="{{ $gearType->id }}" {{ old('gear_type_id') == $gearType->id ? 'selected' : '' }}>
                                                                        {{ $gearType->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('gear_type_id')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="color_id" class="font-weight-bold">Color</label>
                                                            <select name="color_id" id="color_id" class="form-control shadow-sm select2 @error('color_id') is-invalid @enderror">
                                                                <option value="">-- Select Color --</option>
                                                                @foreach($colors as $color)
                                                                    <option value="{{ $color->id }}" style="background-color: {{ $color->color_code }}; color: {{ $color->color_code == '#FFFFFF' ? '#000000' : '#FFFFFF' }};" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                                                                        {{ $color->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('color_id')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="year_id" class="font-weight-bold">Car Year</label>
                                                            <select name="year_id" id="year_id" class="form-control shadow-sm select2">
                                                                <option value="">-- Select Year --</option>
                                                                @foreach($years as $year)
                                                                    <option value="{{ $year->id }}" {{ old('year_id') == $year->id ? 'selected' : '' }}>
                                                                        {{ $year->year }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                            <!-- Car Details -->
                                            <div class="card mb-4">
                                                <div class="card-header bg-light">
                                                    <h3 class="card-title">Car Details</h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="door_count" class="font-weight-bold">Number of Doors</label>
                                                                <input type="number" name="door_count" class="form-control shadow-sm @error('door_count') is-invalid @enderror" max="6" min="1" id="door_count" value="{{ old('door_count') }}">
                                                                @error('door_count')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="luggage_capacity" class="font-weight-bold">Number Of Luggage Capacity</label>
                                                                <input type="number" name="luggage_capacity" class="form-control shadow-sm @error('luggage_capacity') is-invalid @enderror" max="20" min="0" id="luggage_capacity" value="{{ old('luggage_capacity') }}">
                                                                @error('luggage_capacity')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="passenger_capacity" class="font-weight-bold">Number of passenger</label>
                                                                <input type="number" name="passenger_capacity" class="form-control shadow-sm @error('passenger_capacity') is-invalid @enderror" max="20" min="1" id="passenger_capacity" value="{{ old('passenger_capacity') }}">
                                                                @error('passenger_capacity')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <!-- Pricing Information -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Pricing Information</h3>
                                            </div>
                                            <div class="card-body">
                                                <label>Daily: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_main_price" class="font-weight-bold">Daily Main Price</label>
                                                            <input type="number" name="daily_main_price" class="form-control shadow-sm @error('daily_main_price') is-invalid @enderror" id="daily_main_price" value="{{ old('daily_main_price') }}">
                                                            @error('daily_main_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_discount_price" class="font-weight-bold">Daily Price With Discount</label>
                                                            <input type="number" name="daily_discount_price" class="form-control shadow-sm @error('daily_discount_price') is-invalid @enderror" id="daily_discount_price" value="{{ old('daily_discount_price') }}">
                                                            @error('daily_discount_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_mileage_included" class="font-weight-bold">Daily Mileage Included</label>
                                                            <input type="number" name="daily_mileage_included" class="form-control shadow-sm @error('daily_mileage_included') is-invalid @enderror" id="daily_mileage_included" value="{{ old('daily_mileage_included') }}">
                                                            @error('daily_mileage_included')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <label>Weakly: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_main_price" class="font-weight-bold">Weekly Main Price</label>
                                                            <input type="text" name="weekly_main_price" class="form-control shadow-sm @error('weekly_main_price') is-invalid @enderror" id="weekly_main_price" value="{{ old('weekly_main_price') }}">
                                                            @error('weekly_main_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_discount_price" class="font-weight-bold">Weekly Price With Discount</label>
                                                            <input type="number" name="weekly_discount_price" class="form-control shadow-sm @error('weekly_discount_price') is-invalid @enderror" id="weekly_discount_price" value="{{ old('weekly_discount_price') }}">
                                                            @error('weekly_discount_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_mileage_included" class="font-weight-bold">Weekly Mileage Included</label>
                                                            <input type="number" name="weekly_mileage_included" class="form-control shadow-sm @error('weekly_mileage_included') is-invalid @enderror" id="weekly_mileage_included" value="{{ old('weekly_mileage_included') }}">
                                                            @error('weekly_mileage_included')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <label>Monthly: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_main_price" class="font-weight-bold">Monthly Main Price</label>
                                                            <input type="number" name="monthly_main_price" class="form-control shadow-sm @error('monthly_main_price') is-invalid @enderror" id="monthly_main_price" value="{{ old('monthly_main_price') }}">
                                                            @error('monthly_main_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_discount_price" class="font-weight-bold">Monthly Price With Discount</label>
                                                            <input type="number" name="monthly_discount_price" class="form-control shadow-sm @error('monthly_discount_price') is-invalid @enderror" id="monthly_discount_price" value="{{ old('monthly_discount_price') }}">
                                                            @error('monthly_discount_price')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_mileage_included" class="font-weight-bold">Monthly Mileage Included</label>
                                                            <input type="number" name="monthly_mileage_included" class="form-control shadow-sm @error('monthly_mileage_included') is-invalid @enderror" id="monthly_mileage_included" value="{{ old('monthly_mileage_included') }}">
                                                            @error('monthly_mileage_included')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                        <!-- Car Features -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Car Features</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="insurance_included" class="custom-control-input" id="insurance_included" {{ old('insurance_included') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="insurance_included">Insurance Included</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="free_delivery" class="custom-control-input" id="free_delivery" {{ old('free_delivery') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="free_delivery">Free Delivery</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="crypto_payment_accepted" class="custom-control-input" id="crypto_payment_accepted" {{ old('crypto_payment_accepted') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="crypto_payment_accepted">Crypto Payment Accepted</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="is_featured" class="custom-control-input" id="is_featured" {{ old('is_featured') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_featured">Featured</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="is_flash_sale" class="custom-control-input" id="is_flash_sale" {{ old('is_flash_sale') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_flash_sale">Flash Sale</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="only_on_afandina" class="custom-control-input" id="only_on_afandina" {{ old('only_on_afandina') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="only_on_afandina">Only On Afandina</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Car Features</h3>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label for="features" class="font-weight-bold">Features related to Post</label>
                                                        <select class="form-control car-select" name="features[]" multiple="multiple" style="width: 100%;">
                                                            @foreach($features as $feature)
                                                                <option value="{{ $feature->id }}" data-icon="{{ $feature->icon->icon_class }}">
                                                                    {{ $feature->translations()->first()->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <!-- For multiple images -->

                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Upload Media Files</h3>
                                            </div>
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Default Image</label>
                                                            <div class="custom-file">
                                                                <input type="file" name="default_image_path" class="custom-file-input image-upload" id="default_image_path" data-preview="imagePreviewLogo">
                                                                <label class="custom-file-label" for="default_image_path">Upload Default Image</label>
                                                            </div>
                                                            <div class="mt-3">
                                                                <img id="imagePreviewLogo" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='250' height='250' viewBox='0 0 250 250'%3E%3Crect width='100%25' height='100%25' fill='%23ddd'/%3E%3Ctext x='50%25' y='50%25' fill='%23555' font-size='20' text-anchor='middle' dy='.3em'%3E600x200%3C/text%3E%3C/svg%3E" alt="Logo Preview" class="shadow image-rectangle-preview" style="max-height: 250px; width: 250px; object-fit: cover; border: 2px solid #ddd;">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-9">
                                                        <div class="form-group">
                                                            <label for="file_path">Upload Media Files (Images & Videos):</label>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input" name="media[]" id="media-files" multiple accept="image/*,video/mp4,video/webm,video/ogg">
                                                                <label class="custom-file-label" for="media-files">Choose files</label>
                                                            </div>
                                                            <small class="form-text text-muted">
                                                                Supported formats: Images (JPG, PNG, GIF, WebP) and Videos (MP4, WebM, OGG). Maximum file size: 100MB
                                                            </small>
                                                        </div>

                                                        <div id="preview" class="preview-grid mt-3">
                                                            <!-- Preview items will be added here -->
                                                        </div>

                                                        <div class="form-group">
                                                            <label for="alt">Alt Text (for all):</label>
                                                            <input type="text" class="form-control" name="alt" placeholder="Add alt text for images or videos">
                                                        </div>

                                                        <!-- Preview Section -->
                                                        <h2 class="mt-5">Preview</h2>
                                                        <div id="preview" class="row preview-grid"></div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>






                                        <!-- Activation Status -->
                                        <div class="card mb-4">
                                            <div class="card-header bg-light">
                                                <h3 class="card-title">Status</h3>
                                            </div>
                                            <div class="card-body">

                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label class="form-label">Is Active?</label>

                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active') ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_active">Active</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="status" class="font-weight-bold">Car Status</label>
                                                            <select name="status" id="status" class="form-control shadow-sm @error('status') is-invalid @enderror">
                                                                <option value="">-- Select Car Status --</option>
                                                                <option value="available" {{ old('status') == "available" ? 'selected' : '' }}>
                                                                    Available
                                                                </option>
                                                                <option value="not_available" {{ old('status') == "not_available" ? 'selected' : '' }}>
                                                                    Not Available
                                                                </option>
                                                            </select>
                                                            @error('status')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Translated Data Tab Content with Sub-tabs for Languages -->
                                <div class="tab-pane fade" id="custom-tabs-translated" role="tabpanel" aria-labelledby="custom-tabs-translated-tab">
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        @foreach($activeLanguages as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link @if($loop->first) active @endif bg-light text-dark" id="pills-{{ $lang->code }}-tab" data-toggle="pill" href="#pills-{{ $lang->code }}" role="tab" aria-controls="pills-{{ $lang->code }}" aria-selected="true">{{ $lang->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-tabContent">
                                        @foreach($activeLanguages as $lang)
                                            <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-{{ $lang->code }}-tab">
                                                <div class="form-group">
                                                    <label for="name_{{ $lang->code }}" class="font-weight-bold">Name ({{ $lang->name }})</label>
                                                    <input type="text" name="name[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm @error('name.'.$lang->code) is-invalid @enderror" id="name_{{ $lang->code }}" value="{{ old('name.'.$lang->code) }}">
                                                    @error('name.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="description_{{ $lang->code }}" class="font-weight-bold">Description ({{ $lang->name }})</label>
                                                    <textarea name="description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm @error('description.'.$lang->code) is-invalid @enderror" id="description_{{ $lang->code }}" rows="4">{{ old('description.'.$lang->code) }}</textarea>
                                                    @error('description.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>


                                                <div class="form-group">
                                                    <label for="long_description_{{ $lang->code }}" class="font-weight-bold">Long Description ({{ $lang->name }})</label>
                                                    <textarea name="long_description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm teny-editor @error('long_description.'.$lang->code) is-invalid @enderror" id="long_description_{{ $lang->code }}">{{ old('long_description.'.$lang->code) }}</textarea>
                                                    @error('long_description.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- SEO Data Tab Content -->
                                <div class="tab-pane fade" id="custom-tabs-seo" role="tabpanel" aria-labelledby="custom-tabs-seo-tab">
                                    <ul class="nav nav-pills mb-3" id="pills-seo-tab" role="tablist">
                                        @foreach($activeLanguages as $lang)
                                            <li class="nav-item">
                                                <a class="nav-link @if($loop->first) active @endif bg-light text-dark" id="pills-seo-{{ $lang->code }}-tab" data-toggle="pill" href="#pills-seo-{{ $lang->code }}" role="tab" aria-controls="pills-seo-{{ $lang->code }}" aria-selected="true">{{ $lang->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content shadow-sm p-3 mb-4 bg-white rounded" id="pills-seo-tabContent">
                                        @foreach($activeLanguages as $lang)
                                            <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-seo-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-seo-{{ $lang->code }}-tab">
                                                <div class="form-group">
                                                    <label for="meta_title_{{ $lang->code }}" class="font-weight-bold">Meta Title ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_title[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm @error('meta_title.'.$lang->code) is-invalid @enderror" id="meta_title_{{ $lang->code }}" value="{{ old('meta_title.'.$lang->code) }}">
                                                    @error('meta_title.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_description_{{ $lang->code }}" class="font-weight-bold">Meta Description ({{ $lang->name }})</label>
                                                    <textarea name="meta_description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm @error('meta_description.'.$lang->code) is-invalid @enderror" id="meta_description_{{ $lang->code }}" rows="3">{{ old('meta_description.'.$lang->code) }}</textarea>
                                                    @error('meta_description.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                                <<div class="form-group">
                                                    <label for="meta_keywords_{{ $lang->code }}" class="font-weight-bold">Meta Keywords ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_keywords[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm @error('meta_keywords.'.$lang->code) is-invalid @enderror" id="meta_keywords_{{ $lang->code }}" data-role="tagsinput" value="{{ old('meta_keywords.'.$lang->code) }}">
                                                    @error('meta_keywords.'.$lang->code)
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                                <div class="row card">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="robots_index_{{ $lang->code }}" class="font-weight-bold">
                                                                Robot Index ({{ $lang->name }})
                                                            </label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox"
                                                                       name="robots_index[{{ $lang->code }}]"
                                                                       class="custom-control-input"
                                                                       id="robots_index_{{ $lang->code }}"
                                                                       value="index"
                                                                    {{ old('robots_index.'.$lang->code, $currentValues['robots_index'][$lang->code] ?? '') === 'index' ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="robots_index_{{ $lang->code }}">Index</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="robots_follow_{{ $lang->code }}" class="font-weight-bold">
                                                                Robot Follow ({{ $lang->name }})
                                                            </label>
                                                            <div class="custom-control custom-switch">
                                                                <input type="checkbox"
                                                                       name="robots_follow[{{ $lang->code }}]"
                                                                       class="custom-control-input"
                                                                       id="robots_follow_{{ $lang->code }}"
                                                                       value="follow"
                                                                    {{ old('robots_follow.'.$lang->code, $currentValues['robots_follow'][$lang->code] ?? '') === 'follow' ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="robots_follow_{{ $lang->code }}">Follow</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Dynamic SEO Questions/Answers Section -->
                                                <div class="seo-questions-container" id="seo-questions-{{ $lang->code }}">
                                                    <label class="font-weight-bold">SEO Questions/Answers ({{ $lang->name }})</label>
                                                    <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                                                        <div class="form-group">
                                                            <input type="text" name="seo_questions[{{ $lang->code }}][0][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                                                        </div>
                                                        <div class="form-group">
                                                            <textarea name="seo_questions[{{ $lang->code }}][0][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-info add-question mt-3" data-lang="{{ $lang->code }}">
                                                    <i class="fas fa-plus"></i> Add Question
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Array to store selected media files
        let selectedFiles = [];

        // Handle media file preview
        document.getElementById('media-files').addEventListener('change', function(event) {
            var files = Array.from(event.target.files);
            selectedFiles = selectedFiles.concat(files);
            displayMediaPreviews();
        });

        // Function to display media previews
        function displayMediaPreviews() {
            var previewDiv = document.getElementById('preview');
            previewDiv.innerHTML = ''; // Clear previous previews

            selectedFiles.forEach((file, index) => {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let div = document.createElement('div');
                    div.classList.add('preview-item');
                    div.setAttribute('data-index', index);

                    if (file.type.startsWith('image/')) {
                        div.setAttribute('data-type', 'image');
                        div.innerHTML = `
                            <img src="${e.target.result}" class="img-fluid">
                            <button type="button" class="remove-preview" data-type="image" data-index="${index}">×</button>
                        `;
                    } else if (file.type.startsWith('video/')) {
                        div.setAttribute('data-type', 'video');
                        div.innerHTML = `
                            <video src="${e.target.result}" controls class="img-fluid"></video>
                            <button type="button" class="remove-preview" data-type="video" data-index="${index}">×</button>
                        `;
                    }
                    previewDiv.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // Event delegation for preview removal
        document.getElementById('preview').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-preview')) {
                let previewType = event.target.getAttribute('data-type');
                let inputIndex = event.target.getAttribute('data-index');
                let previewItem = event.target.closest('.preview-item');

                if (previewType === 'youtube') {
                    let input = document.querySelector(`.youtube-link[data-index="${inputIndex}"]`);
                    if (input) {
                        input.value = '';
                        delete input.dataset.previewId;
                    }
                } else {
                    selectedFiles.splice(inputIndex, 1);
                    displayMediaPreviews();
                    return;
                }
                previewItem.remove();
            }
        });

        $(document).ready(function() {
            // Function to dynamically add SEO Questions/Answers
            $('.add-question').on('click', function() {
                var lang = $(this).data('lang');
                var container = $('#seo-questions-' + lang);
                var count = container.find('.seo-question-group').length;
                console.log('Adding question for language:', lang, 'Count:', count); // Debugging line
                var newQuestionGroup = `
                    <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                        <div class="form-group">
                            <input type="text" name="seo_questions[` + lang + `][` + count + `][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                        </div>
                        <div class="form-group">
                            <textarea name="seo_questions[` + lang + `][` + count + `][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                    </div>`;
                container.append(newQuestionGroup);
            });

            // Function to remove an SEO Question/Answer
            $(document).on('click', '.remove-question', function() {
                $(this).closest('.seo-question-group').remove();
            });


            @foreach($activeLanguages as $lang)
            var metaKeywordsInput = document.querySelector('#meta_keywords_{{ $lang->code }}');
            if (metaKeywordsInput) {
                new Tagify(metaKeywordsInput, {
                    placeholder: 'Enter meta keywords'
                });
            }
            @endforeach
        });


        $(document).ready(function() {
            $('#brand_id').change(function() {
                var brandId = $(this).val();
                var modelSelect = $('#model_id');

                modelSelect.empty().append('<option value="">-- Select Model --</option>');

                if (brandId) {
                    $.ajax({
                        url: "{{ route('admin.get.models', '') }}/" + brandId,
                        type: "GET",
                        success: function(data) {
                            data.forEach(function(model) {
                                modelSelect.append('<option value="' + model.id + '">' + model.name + '</option>');
                            });
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            // Handle form submission
            $('#createCarForm').on('submit', function(e) {
                e.preventDefault();
                var form = $(this);
                var submitBtn = form.find('button[type="submit"]');
                var formData = new FormData(this);

                // Show loading overlay
                $('#loader-overlay').css('display', 'flex');
                
                // Disable submit button
                submitBtn.prop('disabled', true);
                
                $.ajax({
                    url: form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Hide loader
                        $('#loader-overlay').hide();
                        
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: true,
                                confirmButtonText: 'OK',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = response.redirect;
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        // Hide loading overlay
                        $('#loader-overlay').hide();
                        
                        // Enable submit button
                        submitBtn.prop('disabled', false);

                        var errors = xhr.responseJSON.errors;
                        
                        // Clear previous errors
                        $('.is-invalid').removeClass('is-invalid');
                        $('.invalid-feedback').remove();
                        
                        if (errors) {
                            // Show error alert at the top
                            var errorHtml = '<div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert">' +
                                '<div class="d-flex">' +
                                '<i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px;"></i>' +
                                '<div class="flex-grow-1">' +
                                '<h5 class="alert-heading mb-2">Please correct the following errors:</h5>' +
                                '<ul class="mb-0 pl-3">';
                            
                            $.each(errors, function(key, messages) {
                                messages.forEach(function(message) {
                                    errorHtml += '<li>' + message + '</li>';
                                    
                                    // Add error class and message to form field
                                    var input = $('[name="' + key + '"]');
                                    if (input.length) {
                                        input.addClass('is-invalid');
                                        if (!input.next('.invalid-feedback').length) {
                                            input.after('<div class="invalid-feedback">' + message + '</div>');
                                        }
                                    }
                                });
                            });
                            
                            errorHtml += '</ul></div>' +
                                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                                '<span aria-hidden="true">&times;</span>' +
                                '</button>' +
                                '</div></div>';
                            
                            // Remove any existing error alerts
                            $('.alert-danger').remove();
                            // Add the new error alert at the top of the form
                            form.before(errorHtml);
                        }

                        // Also show in SweetAlert
                        var errorMessage = '<ul class="list-unstyled mb-0">';
                        $.each(errors, function(key, messages) {
                            messages.forEach(function(message) {
                                errorMessage += '<li><i class="fas fa-times-circle mr-2" style="color: #f44336;"></i>' + message + '</li>';
                            });
                        });
                        errorMessage += '</ul>';
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Validation Error!',
                            html: errorMessage,
                            showConfirmButton: true,
                            confirmButtonText: 'OK'
                        });
                    }
                });
            });
        });

        $(document).ready(function() {
            function formatFeature(feature) {
                if (!feature.id) {
                    return feature.text;
                }

                var $feature = $(
                    '<span><i class="' + $(feature.element).data('icon') + '"></i> ' +
                    feature.text + '</span>'
                );
                return $feature;
            }

            $('.car-select').select2({
                templateResult: formatFeature,
                templateSelection: formatFeature,
                allowClear: true,
                placeholder: "Select features"
            });
        });
    </script>
@endpush
