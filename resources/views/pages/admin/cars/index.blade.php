@extends('layouts.admin_layout')

@section('title', 'List of ' . $modelName)

@push('styles')
<style>
    .filter-section {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .filter-section label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 8px;
    }

    .filter-section .form-control {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 8px 12px;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }

    .filter-section .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,0.25);
    }

    .filter-section .btn {
        padding: 8px 15px;
        font-weight: 500;
    }

    .filter-section .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .filter-section .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .filter-section .fas {
        margin-left: 5px;
    }

    /* تحسينات للـ select2 */
    .select2-container--default .select2-selection--single {
        height: 38px;
        line-height: 38px;
        border: 1px solid #ced4da;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    .card {
        box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
        margin-bottom: 1rem;
    }

    .table img.car-thumbnail {
        width: 100px;
        height: 70px;
        object-fit: cover;
        border-radius: 4px;
        transition: transform 0.3s ease;
    }

    .table img.car-thumbnail:hover {
        transform: scale(1.5);
        cursor: pointer;
    }

    .btn-group {
        gap: 5px;
    }
</style>
@endpush

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="display-4">{{ $modelName }} List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">{{ $modelName }} List</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm p-4 rounded-lg" role="alert">
                        <strong>Success:</strong> {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Filter Cars</h4>
                    </div>
                    <div class="card-body">
                        <form id="carFilterForm" method="GET" class="row g-3">
                            <div class="col-md-2">
                                <label for="brand" class="form-label">Brand</label>
                                <select name="brand" id="brand" class="form-control select2">
                                    <option value="">All Brands</option>
                                    @foreach($brands as $brand)
                                        <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                            @php
                                                $translation = $brand->translations->where('locale', 'en')->first();
                                                $name = $translation ? $translation->name : ($brand->translations->first() ? $brand->translations->first()->name : 'N/A');
                                            @endphp
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="model" class="form-label">Model</label>
                                <select name="model" id="model" class="form-control select2" {{ !request('brand') ? 'disabled' : '' }}>
                                    <option value="">All Models</option>
                                    @if(request('brand'))
                                        @foreach($brands->find(request('brand'))->carModels as $model)
                                            @php
                                                $translation = $model->translations->where('locale', 'en')->first();
                                                $name = $translation ? $translation->name : ($model->translations->first() ? $model->translations->first()->name : 'N/A');
                                            @endphp
                                            <option value="{{ $model->id }}" {{ request('model') == $model->id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="category" class="form-label">Category</label>
                                <select name="category" id="category" class="form-control select2">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        @php
                                            $translation = $category->translations->where('locale', 'en')->first();
                                            $name = $translation ? $translation->name : ($category->translations->first() ? $category->translations->first()->name : 'N/A');
                                        @endphp
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="year" class="form-label">Year</label>
                                <select name="year" id="year" class="form-control select2">
                                    <option value="">All Years</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year->id }}" {{ request('year') == $year->id ? 'selected' : '' }}>
                                            {{ $year->year }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label for="search" class="form-label">Search</label>
                                <input type="text" name="search" id="search" class="form-control"
                                       placeholder="Search by name..."
                                       value="{{ request('search') }}">
                            </div>

                            <div class="col-md-2 d-flex align-items-end">
                                <div class="btn-group w-100">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                    <a href="{{ route('admin.cars.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Cars List -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h4 class="card-title">Cars List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Model</th>
                                    <th>Category</th>
                                    <th>Year</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($item->default_image_path)
                                                <img src="{{ asset('storage/' . $item->default_image_path) }}"
                                                     alt="Car Image"
                                                     class="car-thumbnail"
                                                     data-toggle="tooltip"
                                                     title="Click to enlarge">
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $translation = $item->translations->where('locale', 'en')->first();
                                                $name = $translation ? $translation->name : ($item->translations->first() ? $item->translations->first()->name : 'N/A');
                                            @endphp
                                            {{ $name }}
                                        </td>
                                        <td>
                                            @php
                                                $brand = $item->brand;
                                                $brandName = $brand && $brand->translations ?
                                                    ($brand->translations->where('locale', 'en')->first()?->name ??
                                                     $brand->translations->first()?->name ?? 'N/A') : 'N/A';
                                            @endphp
                                            {{ $brandName }}
                                        </td>
                                        <td>
                                            @php
                                                $model = $item->carModel;
                                                $modelName = $model && $model->translations ?
                                                    ($model->translations->where('locale', 'en')->first()?->name ??
                                                     $model->translations->first()?->name ?? 'N/A') : 'N/A';
                                            @endphp
                                            {{ $modelName }}
                                        </td>
                                        <td>
                                            @php
                                                $category = $item->category;
                                                $categoryName = $category && $category->translations ?
                                                    ($category->translations->where('locale', 'en')->first()?->name ??
                                                     $category->translations->first()?->name ?? 'N/A') : 'N/A';
                                            @endphp
                                            {{ $categoryName }}
                                        </td>
                                        <td>{{ optional($item->year)->year ?? 'N/A' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('admin.cars.edit', $item->id) }}"
                                                   class="btn btn-sm btn-primary"
                                                   data-toggle="tooltip"
                                                   title="Edit Car">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('admin.cars.edit_images', $item->id) }}"
                                                   class="btn btn-sm btn-info"
                                                   data-toggle="tooltip"
                                                   title="Manage Images">
                                                    <i class="fas fa-images"></i>
                                                </a>
                                                <form action="{{ route('admin.cars.destroy', $item->id) }}"
                                                      method="POST"
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            data-toggle="tooltip"
                                                            title="Delete Car"
                                                            onclick="return confirm('Are you sure you want to delete this car?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">No cars found</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $items->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // تفعيل tooltips
        $('[data-toggle="tooltip"]').tooltip();

        // تفعيل select2 للقوائم المنسدلة
        $('.select2').select2({
            width: '100%',
            dir: 'rtl'
        });

        // تحديث الموديلات عند تغيير الماركة
        $('#brand').on('change', function() {
            var brandId = $(this).val();
            var modelSelect = $('#model');

            // إعادة تعيين قائمة الموديلات
            modelSelect.empty().append('<option value="">All Models</option>');

            if (brandId) {
                // تفعيل قائمة الموديلات
                modelSelect.prop('disabled', false);

                // جلب الموديلات من السيرفر
                $.get('/admin/cars/models/' + brandId, function(models) {
                    models.forEach(function(model) {
                        modelSelect.append(new Option(model.name, model.id));
                    });
                    modelSelect.trigger('change');
                });
            } else {
                // تعطيل قائمة الموديلات إذا لم يتم اختيار ماركة
                modelSelect.prop('disabled', true);
            }
        });

        // إزالة الأحداث التلقائية وجعل البحث فقط عند الضغط على زر التصفية
        $('#carFilterForm').on('submit', function(e) {
            e.preventDefault();
            this.submit();
        });
    });
</script>
@endpush
