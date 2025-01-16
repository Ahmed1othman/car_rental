@extends('layouts.admin_layout')
@section('title', 'Edit ' . $modelName)
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

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush
@section('content')
    <!-- Loader Overlay -->
    <div class="loader-overlay" id="loader-overlay">
        <div class="text-center">
            <div class="loader"></div>
            <p class="mt-2">Processing...</p>
        </div>
    </div>
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4">Edit {{$modelName}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.' . $modelName . '.index') }}" style="text-transform: capitalize;">{{ $modelName }} List</a></li>
                            <li class="breadcrumb-item active" style="text-transform: capitalize;">Edit {{$modelName}}</li>
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
                        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active text-dark" id="custom-tabs-general-tab" data-toggle="pill" href="#custom-tabs-general" role="tab" aria-controls="custom-tabs-general" aria-selected="true">
                                    <i class="fas fa-info-circle"></i> General Data
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-translated-tab" data-toggle="pill" href="#custom-tabs-translated" role="tab" aria-controls="custom-tabs-translated" aria-selected="false">
                                    <i class="fas fa-language"></i> Translated Data
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-dark" id="custom-tabs-seo-tab" data-toggle="pill" href="#custom-tabs-seo" role="tab" aria-controls="custom-tabs-seo" aria-selected="false">
                                    <i class="fas fa-search"></i> SEO Data
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <form id="editCarForm" action="{{ route('admin.'.$modelName.'.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="tab-content" id="custom-tabs-three-tabContent">
                                <div class="tab-pane fade show active" id="custom-tabs-general" role="tabpanel" aria-labelledby="custom-tabs-general-tab">
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
                                                                <option value="{{ $brand->id }}" {{ (old('brand_id', $item->brand_id) == $brand->id) ? 'selected' : '' }}>
                                                                    {{ $brand->translations()->first()->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="car_model_id" class="font-weight-bold">Model</label>
                                                        <select name="car_model_id" id="car_model_id" class="form-control shadow-sm select2" data-selected="{{ old('car_model_id', $item->car_model_id) }}">
                                                            <option value="">-- Select Model --</option>
                                                            <!-- Options will be populated dynamically via JavaScript -->
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="category_id" class="font-weight-bold">Car Category</label>
                                                        <select name="category_id" id="category_id" class="form-control shadow-sm select2">
                                                            <option value="">-- Select Category --</option>
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" {{ old('category_id', $item->category_id) == $category->id ? 'selected' : '' }}>
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
                                                                <option value="{{ $gearType->id }}" {{ old('gear_type_id', $item->gear_type_id) == $gearType->id ? 'selected' : '' }}>
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
                                                                <option value="{{ $color->id }}" style="background-color: {{ $color->color_code }}; color: {{ $color->color_code == '#FFFFFF' ? '#000000' : '#FFFFFF' }};" {{ old('color_id', $item->color_id) == $color->id ? 'selected' : '' }}>
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
                                                                <option value="{{ $year->id }}" {{ (string)old('year_id', $item->year_id) === (string)$year->id ? 'selected' : '' }}>
                                                                    {{ $year->year }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="status" class="font-weight-bold">Status</label>
                                                        <select name="status" id="status" class="form-control shadow-sm">
                                                            <option value="">-- Select Status --</option>
                                                            <option value="available" {{ old('status', $item->status) == 'available' ? 'selected' : '' }}>Available</option>
                                                            <option value="not_available" {{ old('status', $item->status) == 'not_available' ? 'selected' : '' }}>Not Available</option>
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
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="door_count" class="font-weight-bold">Number of Doors</label>
                                                        <input type="number" name="door_count" class="form-control shadow-sm" id="door_count" value="{{ old('door_count', $item->door_count) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="luggage_capacity" class="font-weight-bold">Number of luggage Capacity</label>
                                                        <input type="number" name="luggage_capacity" class="form-control shadow-sm" id="luggage_capacity" value="{{ old('luggage_capacity', $item->luggage_capacity) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="passenger_capacity" class="font-weight-bold">Number of Passengers</label>
                                                        <input type="number" name="passenger_capacity" class="form-control shadow-sm" id="passenger_capacity" max="20" min="1" value="{{ old('passenger_capacity', $item->passenger_capacity) }}">
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
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="daily_main_price" class="font-weight-bold">Daily Main Price</label>
                                                        <input type="text" name="daily_main_price" class="form-control shadow-sm" id="daily_main_price" value="{{ old('daily_main_price', $item->daily_main_price) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="daily_discount_price" class="font-weight-bold">Daily Price With Discount</label>
                                                        <input type="text" name="daily_discount_price" class="form-control shadow-sm" id="daily_discount_price" value="{{ old('daily_discount_price', $item->daily_discount_price) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="daily_mileage_included" class="font-weight-bold">Daily Mileage Included</label>
                                                        <input type="number" name="daily_mileage_included" class="form-control shadow-sm" id="daily_mileage_included" value="{{ old('daily_mileage_included', $item->daily_mileage_included) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="weekly_main_price" class="font-weight-bold">Weekly Main Price</label>
                                                        <input type="text" name="weekly_main_price" class="form-control shadow-sm" id="weekly_main_price" value="{{ old('weekly_main_price', $item->weekly_main_price) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="weekly_discount_price" class="font-weight-bold">Weekly Price With Discount</label>
                                                        <input type="text" name="weekly_discount_price" class="form-control shadow-sm" id="weekly_discount_price" value="{{ old('weekly_discount_price', $item->weekly_discount_price) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="weekly_mileage_included" class="font-weight-bold">Weakly Mileage Included</label>
                                                        <input type="number" name="weekly_mileage_included" class="form-control shadow-sm" id="weekly_mileage_included" value="{{ old('weekly_mileage_included', $item->weekly_mileage_included) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="monthly_main_price" class="font-weight-bold">Monthly Main Price</label>
                                                        <input type="text" name="monthly_main_price" class="form-control shadow-sm" id="monthly_main_price" value="{{ old('monthly_main_price', $item->monthly_main_price) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="monthly_discount_price" class="font-weight-bold">Monthly Price With Discount</label>
                                                        <input type="text" name="monthly_discount_price" class="form-control shadow-sm" id="monthly_discount_price" value="{{ old('monthly_discount_price', $item->monthly_discount_price) }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="monthly_mileage_included" class="font-weight-bold">Monthly Mileage Included</label>
                                                        <input type="number" name="monthly_mileage_included" class="form-control shadow-sm" id="monthly_mileage_included" value="{{ old('monthly_mileage_included', $item->monthly_mileage_included) }}">
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
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="insurance_included" class="custom-control-input" id="insurance_included" {{ old('insurance_included', $item->insurance_included) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="insurance_included">Insurance Included</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="free_delivery" class="custom-control-input" id="free_delivery" {{ old('free_delivery', $item->free_delivery) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="free_delivery">Free Delivery</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="crypto_payment_accepted" class="custom-control-input" id="crypto_payment_accepted" {{ old('crypto_payment_accepted', $item->crypto_payment_accepted) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="crypto_payment_accepted">Crypto Payment Accepted</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="is_featured" class="custom-control-input" id="is_featured" {{ old('is_featured', $item->is_featured) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_featured">Featured</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="is_flash_sale" class="custom-control-input" id="is_flash_sale" {{ old('is_flash_sale', $item->is_flash_sale) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_flash_sale">Flash Sale</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="only_on_afandina" class="custom-control-input" id="only_on_afandina" {{ old('is_flash_sale', $item->only_on_afandina) ? 'checked' : '' }}>
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
                                                @php
                                                    $selectedFeatures = \App\Models\CarFeature::where('car_id', $item->id)->pluck('feature_id')->toArray();
                                                @endphp
                                                <div class="form-group">
                                                    <label for="features" class="font-weight-bold">Features related to Post</label>
                                                    <select class="form-control feature-select" name="features[]" multiple="multiple" style="width: 100%;">
                                                        @foreach($features as $feature)
                                                            <option value="{{ $feature->id }}" data-icon="{{ $feature->icon->icon_class }}"
                                                                {{ in_array($feature->id, $selectedFeatures) ? 'selected' : '' }}>
                                                            {{ $feature->translations->first()->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card mb-4">
                                        <div class="card-header bg-light">
                                            <h3 class="card-title">Status</h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="form-group col-md-6">
                                                    <label class="form-label">Is Active?</label>
                                                    <div class="custom-control custom-switch">
                                                        <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" {{ old('is_active', $item->is_active) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="is_active">Active</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="status" class="font-weight-bold">Car Status</label>
                                                        <select name="status" id="status" class="form-control shadow-sm">
                                                            <option value="">-- Select Car Status --</option>
                                                            <option value="available" {{ old('status', $item->status) == "available" ? 'selected' : '' }}>Available</option>
                                                            <option value="not_available" {{ old('status', $item->status) == "not_available" ? 'selected' : '' }}>Not Available</option>
                                                        </select>
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
                                            @php
                                                $translation = $item->translations->where('locale', $lang->code)->first();
                                            @endphp
                                            <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-{{ $lang->code }}-tab">
                                                <div class="form-group">
                                                    <label for="name_{{ $lang->code }}" class="font-weight-bold">Name ({{ $lang->name }})</label>
                                                    <input type="text" name="name[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="name_{{ $lang->code }}" value="{{ old('name.'.$lang->code, $translation->name??'') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="description_{{ $lang->code }}" class="font-weight-bold">Description ({{ $lang->name }})</label>
                                                    <textarea name="description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="description_{{ $lang->code }}" rows="4">{{ old('description.'.$lang->code, $translation->description??'') }}</textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="long_description_{{ $lang->code }}" class="font-weight-bold">Long Description ({{ $lang->name }})</label>
                                                    <textarea name="translations[{{ $lang->code }}][long_description]" class="form-control form-control-lg shadow-sm ckeditor" id="long_description_{{ $lang->code }}" rows="6">{{ old('translations.'.$lang->code.'.long_description', $translation->long_description ?? '') }}</textarea>
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
                                            @php
                                                $translation = $item->translations->where('locale', $lang->code)->first();
                                            @endphp
                                            <div class="tab-pane fade @if($loop->first) show active @endif" id="pills-seo-{{ $lang->code }}" role="tabpanel" aria-labelledby="pills-seo-{{ $lang->code }}-tab">
                                                <div class="form-group">
                                                    <label for="meta_title_{{ $lang->code }}" class="font-weight-bold">Meta Title ({{ $lang->name }})</label>
                                                    <input type="text" name="meta_title[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="meta_title_{{ $lang->code }}" value="{{ old('meta_title.' . $lang->code, $translation->meta_title ?? '') }}">
                                                </div>
                                                <div class="form-group">
                                                    <label for="meta_description_{{ $lang->code }}" class="font-weight-bold">Meta Description ({{ $lang->name }})</label>
                                                    <textarea name="meta_description[{{ $lang->code }}]" class="form-control form-control-lg shadow-sm" id="meta_description_{{ $lang->code }}" rows="3">{{ old('meta_description.' . $lang->code, $translation->meta_description ?? '') }}</textarea>
                                                </div>
                                                <!-- Meta Keywords Field -->
                                                <!-- Meta Keywords Field -->
                                                <div class="form-group">
                                                    <label for="meta_keywords_{{ $lang->code }}" class="font-weight-bold">Meta Keywords ({{ $lang->name }})</label>
                                                    @php
                                                        // Decode the JSON meta_keywords into an array
                                                        $metaKeywords = json_decode($translation->meta_keywords ?? '[]', true);
                                                        // Convert the array into a comma-separated string of keywords
                                                        $keywordString = implode(',', array_column($metaKeywords, 'value'));
                                                    @endphp
                                                    <input type="text" name="meta_keywords[{{ $lang->code }}]"
                                                           class="form-control form-control-lg shadow-sm"
                                                           id="meta_keywords_{{ $lang->code }}"
                                                           value="{{ old('meta_keywords.' . $lang->code, $keywordString) }}"
                                                           data-role="tagsinput" placeholder="Enter meta keywords">
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
                                                                    {{ old('meta_title.' . $lang->code, $translation->robots_index ?? '') === 'index' ? 'checked' : '' }}>
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
                                                                    {{ old('meta_title.' . $lang->code, $translation->robots_follow ?? '') === 'follow' ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="robots_follow_{{ $lang->code }}">Follow</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- Dynamic SEO Questions/Answers Section -->
                                                <div class="seo-questions-container" id="seo-questions-{{ $lang->code }}">
                                                    <label class="font-weight-bold">SEO Questions/Answers ({{ $lang->name }})</label>
                                                    @foreach($item->seoQuestions->where('locale', $lang->code) as $index => $seoQuestion)
                                                        <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                                                            <div class="form-group">
                                                                <input type="text" name="seo_questions[{{ $lang->code }}][{{ $index }}][question]" class="form-control form-control-lg shadow-sm mb-2" value="{{ old('seo_questions.' . $lang->code . '.' . $index . '.question', $seoQuestion->question_text) }}" placeholder="Enter Question" />
                                                            </div>
                                                            <div class="form-group">
                                                                <textarea name="seo_questions[{{ $lang->code }}][{{ $index }}][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer">{{ old('seo_questions.' . $lang->code . '.' . $index . '.answer', $seoQuestion->answer_text) }}</textarea>
                                                            </div>
                                                            <button type="button" class="btn btn-sm btn-danger remove-question">Remove</button>
                                                        </div>
                                                    @endforeach
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
                                <i class="fas fa-save"></i> Update
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
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
    <script>
        $(document).ready(function() {
            $('#editCarForm').on('submit', function(e) {
                e.preventDefault();

                // Update CKEditor instances
                for (var instanceName in CKEDITOR.instances) {
                    CKEDITOR.instances[instanceName].updateElement();
                }

                showLoader();

                let formData = new FormData(this);
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        hideLoader();
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = response.redirect;
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        hideLoader();
                        let errorHtml = '<div class="alert alert-danger alert-dismissible fade show shadow-sm mt-3 p-4 rounded-lg" role="alert">';
                        errorHtml += '<div class="d-flex">';
                        errorHtml += '<i class="fas fa-exclamation-triangle mr-2" style="font-size: 24px;"></i>';
                        errorHtml += '<div class="flex-grow-1">';
                        errorHtml += '<h5 class="alert-heading mb-2">Please correct the following errors:</h5>';
                        errorHtml += '<ul class="mb-0 pl-3">';
                        
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            // Loop through all error messages
                            Object.entries(xhr.responseJSON.errors).forEach(([field, messages]) => {
                                messages.forEach(message => {
                                    errorHtml += '<li>' + message + '</li>';
                                });
                            });
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorHtml += '<li>' + xhr.responseJSON.message + '</li>';
                        } else {
                            errorHtml += '<li>An unexpected error occurred. Please try again.</li>';
                        }
                        
                        errorHtml += '</ul>';
                        errorHtml += '</div>';
                        errorHtml += '<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
                        errorHtml += '<span aria-hidden="true">&times;</span>';
                        errorHtml += '</button>';
                        errorHtml += '</div>';
                        errorHtml += '</div>';

                        // Remove any existing error alerts
                        $('.alert-danger').remove();
                        // Add the new error alert at the top of the container
                        $('.content .container-fluid').first().prepend(errorHtml);
                        
                        // Scroll to the top of the page to show the errors
                        $('html, body').animate({
                            scrollTop: 0
                        }, 'slow');
                    }
                });
            });
        });

        function showLoader() {
            $('#loader-overlay').css('display', 'flex');
        }

        function hideLoader() {
            $('#loader-overlay').css('display', 'none');
        }
    </script>
    <script>
        $(document).ready(function() {
            // Function to dynamically add SEO Questions/Answers
            $('.add-question').on('click', function() {
                var lang = $(this).data('lang');
                var container = $('#seo-questions-' + lang);
                var count = container.find('.seo-question-group').length;
                var newQuestionGroup = `
                    <div class="seo-question-group mb-3 p-3 border border-light rounded shadow-sm">
                        <div class="form-group">
                            <input type="text" name="seo_questions[${lang}][${count}][question]" class="form-control form-control-lg shadow-sm mb-2" placeholder="Enter Question" />
                        </div>
                        <div class="form-group">
                            <textarea name="seo_questions[${lang}][${count}][answer]" class="form-control form-control-lg shadow-sm" placeholder="Enter Answer"></textarea>
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
            // Initialize CKEditor for each language
            @foreach($activeLanguages as $lang)
                CKEDITOR.replace('long_description_{{ $lang->code }}', {
                    height: 300,
                    removeButtons: 'Save,Form,About',
                    allowedContent: true
                });
            @endforeach

            // Ensure CKEditor content is updated before form submission
            $('#editCarForm').on('submit', function() {
                @foreach($activeLanguages as $lang)
                    var editor = CKEDITOR.instances['long_description_{{ $lang->code }}'];
                    if (editor) {
                        editor.updateElement();
                    }
                @endforeach
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            var brandSelect = $('#brand_id');
            var modelSelect = $('#car_model_id');
            var initialModelId = '{{ old('car_model_id', $item->car_model_id) }}';

            function loadModels(brandId, selectedModelId = null) {
                modelSelect.empty().append('<option value="">-- Select Model --</option>');
                if (brandId) {
                    $.ajax({
                        url: "{{ route('admin.get.models', '') }}/" + brandId,
                        type: "GET",
                        success: function(data) {
                            data.forEach(function(model) {
                                var selected = (selectedModelId && selectedModelId == model.id) ? 'selected' : '';
                                modelSelect.append('<option value="' + model.id + '" ' + selected + '>' + model.name + '</option>');
                            });
                            // Set the selected value after populating options
                            if (selectedModelId) {
                                modelSelect.val(selectedModelId).trigger('change');
                            }
                        }
                    });
                }
            }

            // Load models on brand change
            brandSelect.change(function() {
                var selectedBrandId = $(this).val();
                // When brand changes, only pass the selected model if it's the initial load
                loadModels(selectedBrandId, $(this).data('initial-load') ? initialModelId : null);
                $(this).removeData('initial-load');
            });

            // Load models on page load if a brand is already selected
            var initialBrandId = brandSelect.val();
            if (initialBrandId) {
                // Set a flag for initial load
                brandSelect.data('initial-load', true);
                loadModels(initialBrandId, initialModelId);
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            function formatFeature(feature) {
                if (!feature.id) {
                    return feature.text;
                }
                var $feature = $('<span><i class="' + $(feature.element).data('icon') + '"></i> ' + feature.text + '</span>');
                return $feature;
            }
            $('.feature-select').select2({
                templateResult: formatFeature,
                templateSelection: formatFeature,
                allowClear: true,
                placeholder: "Select features"
            });
        });
    </script>
@endpush