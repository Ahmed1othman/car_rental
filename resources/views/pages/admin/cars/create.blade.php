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
            max-width: 100%;
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
    </style>
@endpush
@section('content')
    <!-- Add the styles here -->

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
                        <form action="{{ route('admin.'.$modelName.'.store') }}" method="POST" enctype="multipart/form-data">
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
                                                            <select name="brand_id" id="brand_id" class="form-control shadow-sm select2">
                                                                <option value="">-- Select Brand --</option>
                                                                @foreach($brands as $brand)
                                                                    <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                                        {{ $brand->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
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
                                                            <select name="category_id" id="category_id" class="form-control shadow-sm select2">
                                                                <option value="">-- Select Category --</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                                        {{ $category->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="gearType_id" class="font-weight-bold">Gear Type</label>
                                                            <select name="gear_type_id" id="gear_type_id" class="form-control shadow-sm select2">
                                                                <option value="">-- Select Gear Type --</option>
                                                                @foreach($gearTypes as $gearType)
                                                                    <option value="{{ $gearType->id }}" {{ old('gear_type_id') == $gearType->id ? 'selected' : '' }}>
                                                                        {{ $gearType->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="color_id" class="font-weight-bold">Color</label>
                                                            <select name="color_id" id="color_id" class="form-control shadow-sm select2">
                                                                <option value="">-- Select Color --</option>
                                                                @foreach($colors as $color)
                                                                    <option value="{{ $color->id }}" style="background-color: {{ $color->color_code }}; color: {{ $color->color_code == '#FFFFFF' ? '#000000' : '#FFFFFF' }};" {{ old('color_id') == $color->id ? 'selected' : '' }}>
                                                                        {{ $color->translations()->first()->name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
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
                                                                <input type="number" name="door_count" class="form-control shadow-sm" max="6" min="1" id="door_count" value="{{ old('door_count') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="luggage_capacity" class="font-weight-bold">Number Of Luggage Capacity</label>
                                                                <input type="number" name="luggage_capacity" class="form-control shadow-sm" max="20" min="0" id="luggage_capacity" value="{{ old('luggage_capacity') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="passenger_capacity" class="font-weight-bold">Number of passenger</label>
                                                                <input type="number" name="passenger_capacity" class="form-control shadow-sm" max="20" min="1" id="passenger_capacity" value="{{ old('passenger_capacity') }}">
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
                                                            <input type="number" name="daily_main_price" class="form-control shadow-sm" id="daily_main_price" value="{{ old('daily_main_price') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_discount_price" class="font-weight-bold">Daily Price With Discount</label>
                                                            <input type="number" name="daily_discount_price" class="form-control shadow-sm" id="daily_discount_price" value="{{ old('daily_discount_price') }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="daily_mileage_included" class="font-weight-bold">Daily Mileage Included</label>
                                                            <input type="number" name="daily_mileage_included" class="form-control shadow-sm" id="daily_mileage_included" value="{{ old('daily_mileage_included') }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <label>Weakly: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_main_price" class="font-weight-bold">Weekly Main Price</label>
                                                            <input type="text" name="weekly_main_price" class="form-control shadow-sm" id="weekly_main_price" value="{{ old('weekly_main_price') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_discount_price" class="font-weight-bold">Weekly Price With Discount</label>
                                                            <input type="number" name="weekly_discount_price" class="form-control shadow-sm" id="weekly_discount_price" value="{{ old('weekly_discount_price') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="weekly_mileage_included" class="font-weight-bold">Weekly Mileage Included</label>
                                                            <input type="number" name="weekly_mileage_included" class="form-control shadow-sm" id="weekly_mileage_included" value="{{ old('weekly_mileage_included') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <label>Monthly: </label>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_main_price" class="font-weight-bold">Monthly Main Price</label>
                                                            <input type="number" name="monthly_main_price" class="form-control shadow-sm" id="monthly_main_price" value="{{ old('monthly_main_price') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_discount_price" class="font-weight-bold">Monthly Price With Discount</label>
                                                            <input type="number" name="monthly_discount_price" class="form-control shadow-sm" id="monthly_discount_price" value="{{ old('monthly_discount_price') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="monthly_mileage_included" class="font-weight-bold">Monthly Mileage Included</label>
                                                            <input type="number" name="monthly_mileage_included" class="form-control shadow-sm" id="monthly_mileage_included" value="{{ old('monthly_mileage_included') }}">
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
                                                                    {{ $feature->translations->first()->name }}
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
                                                <h3 class="card-title">Upload Car Images</h3>
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
                                                            <label for="file_path">Upload Images:</label>
                                                            <input type="file" class="form-control" name="images[]" id="image-files" multiple>
                                                        </div>

                                                        <!-- For multiple YouTube links -->
                                                        <div class="form-group">
                                                            <label for="youtube_links">YouTube Links:</label>
                                                            <div id="youtube-links">
                                                                <input type="text" class="form-control youtube-link" name="youtube_links[]" placeholder="Enter YouTube link" data-index="0">
                                                            </div>
                                                            <button type="button" class="btn btn-secondary mt-2" id="add-link">Add Another Link</button>
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
                                                            <select name="status" id="status" class="form-control shadow-sm">
                                                                <option value="">-- Select Car Status --</option>
                                                                <option value="available" {{ old('status') == "available" ? 'selected' : '' }}>
                                                                    Available
                                                                </option>
                                                                <option value="not_available" {{ old('status') == "not_available" ? 'selected' : '' }}>
                                                                    Not Available
                                                                </option>
                                                            </select>
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
                                                    <input type="text" name="name[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="name_{{ $lang->code }}" value="{{ old('name.'.$lang->code) }}">
                                                </div>

                                                <div class="form-group">
                                                    <label for="description_{{ $lang->code }}" class="font-weight-bold">Description ({{ $lang->name }})</label>
                                                    <textarea name="description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="description_{{ $lang->code }}" rows="4">{{ old('description.'.$lang->code) }}</textarea>
                                                </div>


                                                <div class="form-group">
                                                    <label for="long_description_{{ $lang->code }}" class="font-weight-bold">Long Description ({{ $lang->name }})</label>
                                                    <textarea name="long_description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm teny-editor" id="long_description_{{ $lang->code }}">{{ old('long_description.'.$lang->code) }}</textarea>
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
                                                    <input type="text" name="meta_title[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="meta_title_{{ $lang->code }}" value="{{ old('meta_title.'.$lang->code) }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_description_{{ $lang->code }}" class="font-weight-bold">Meta Description ({{ $lang->name }})</label>
                                                    <textarea name="meta_description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="meta_description_{{ $lang->code }}" rows="3">{{ old('meta_description.'.$lang->code) }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_keywords_{{ $lang->code }}" class="font-weight-bold">Meta Keywords ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_keywords[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="meta_keywords_{{ $lang->code }}" data-role="tagsinput" value="{{ old('meta_keywords.'.$lang->code) }}">
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
    <script>
        // Array to store selected images
        let selectedFiles = [];

        // Handle image preview
        document.getElementById('image-files').addEventListener('change', function(event) {
            var files = Array.from(event.target.files); // Convert FileList to Array
            selectedFiles = selectedFiles.concat(files); // Add new files to the array
            displayImagePreviews(); // Update previews
        });

        // Function to display image previews
        function displayImagePreviews() {
            var previewDiv = document.getElementById('preview');
            previewDiv.innerHTML = ''; // Clear previous previews

            selectedFiles.forEach((file, index) => {
                let reader = new FileReader();
                reader.onload = function(e) {
                    let div = document.createElement('div');
                    div.classList.add('preview-item');
                    div.setAttribute('data-type', 'image');
                    div.setAttribute('data-index', index);
                    div.innerHTML = `
                    <img src="${e.target.result}" class="img-fluid">
                    <button type="button" class="remove-preview" data-type="image" data-index="${index}">×</button>
                `;
                    previewDiv.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // Handle YouTube link addition
        document.getElementById('add-link').addEventListener('click', function() {
            var youtubeLinksDiv = document.getElementById('youtube-links');
            var emptyInput = Array.from(document.querySelectorAll('.youtube-link')).some(input => input.value.trim() === '');

            if (emptyInput) {
                alert('Please fill in the empty YouTube link field before adding another one.');
            } else {
                var newIndex = document.querySelectorAll('.youtube-link').length;
                var newInput = document.createElement('input');
                newInput.setAttribute('type', 'text');
                newInput.setAttribute('class', 'form-control youtube-link mt-2');
                newInput.setAttribute('name', 'youtube_links[]');
                newInput.setAttribute('placeholder', 'Enter YouTube link');
                newInput.setAttribute('data-index', newIndex); // Add unique index for tracking
                youtubeLinksDiv.appendChild(newInput);
            }
        });

        // Handle real-time YouTube link preview
        document.getElementById('youtube-links').addEventListener('input', function(event) {
            if (event.target.matches('.youtube-link')) {
                var previewDiv = document.getElementById('preview');

                // Clear previous preview related to the same input
                let currentPreview = event.target.closest('.youtube-link').dataset.previewId;
                if (currentPreview) {
                    document.getElementById(currentPreview).remove();
                }

                var embedUrl = getYouTubeEmbedUrl(event.target.value.trim());
                if (embedUrl) {
                    let div = document.createElement('div');
                    let previewId = 'youtube-preview-' + Math.random().toString(36).substring(2, 9);
                    div.classList.add('preview-item');
                    div.id = previewId;
                    div.setAttribute('data-type', 'video');
                    div.setAttribute('data-index', event.target.getAttribute('data-index')); // Link to input
                    div.innerHTML = `
                    <iframe src="${embedUrl}" frameborder="0" allowfullscreen></iframe>
                    <button type="button" class="remove-preview" data-type="video" data-index="${event.target.getAttribute('data-index')}">×</button>
                `;
                    previewDiv.appendChild(div);
                    // Store the preview ID in the input's dataset for future reference
                    event.target.dataset.previewId = previewId;
                }
            }
        });

        // Event delegation to handle preview removal for both images and videos
        document.getElementById('preview').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-preview')) {
                let previewType = event.target.getAttribute('data-type');
                let inputIndex = event.target.getAttribute('data-index');

                // If removing a video, remove the corresponding YouTube input
                if (previewType === 'video') {
                    let input = document.querySelector(`.youtube-link[data-index="${inputIndex}"]`);
                    if (input) {
                        input.remove();
                    }
                }

                // If removing an image, handle image input reset
                if (previewType === 'image') {
                    selectedFiles.splice(inputIndex, 1); // Remove the image from the array
                    displayImagePreviews(); // Update previews
                    resetFileInput(); // Update the file input to match the remaining selected files
                }

                // Remove the preview item
                event.target.closest('.preview-item').remove();
            }
        });

        // Reset file input with remaining files
        function resetFileInput() {
            const dataTransfer = new DataTransfer(); // Use DataTransfer to manipulate the file input
            selectedFiles.forEach((file) => {
                dataTransfer.items.add(file); // Add remaining files back to the file input
            });

            // Update the file input with the new DataTransfer object
            document.getElementById('image-files').files = dataTransfer.files;
        }

        // Function to get YouTube embed URL from link
        function getYouTubeEmbedUrl(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length == 11) ? `https://www.youtube.com/embed/${match[2]}` : null;
        }



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

    </script>

    <script>
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
